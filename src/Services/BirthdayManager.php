<?php

namespace Azuriom\Plugin\Birthday\Services;

use Azuriom\Models\User;
use Azuriom\Notifications\AlertNotification;
use Azuriom\Plugin\Birthday\Models\Birthday;
use Azuriom\Plugin\Birthday\Models\Claim;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class BirthdayManager
{
    /**
     * Save the birthday of a player. A player can do it only once: the staff can change it afterwards.
     * Throws a RuntimeException with a translation key when it is not possible.
     */
    public static function register(User $user, int $day, int $month, bool $trusted = false): Birthday
    {
        // 2000 is a leap year, so February 29th is accepted.
        if (! checkdate($month, $day, 2000)) {
            throw new RuntimeException('birthday::messages.errors.date');
        }

        try {
            $birthday = Birthday::create([
                'user_id' => $user->id,
                'day' => $day,
                'month' => $month,
                'trusted' => $trusted,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new RuntimeException('birthday::messages.errors.already_saved');
        }

        // A birthday saved on the day itself is notified at the next request.
        Cache::forget(self::checkKey($user));

        return $birthday;
    }

    /**
     * The state of the birthday for the page of the player.
     *
     * @return array{state: string, date?: Carbon, next?: Carbon, deadline?: Carbon, days?: int, reason?: string|null}
     */
    public static function status(?Birthday $birthday): array
    {
        if ($birthday === null) {
            return ['state' => 'none'];
        }

        $grace = RewardSettings::graceDays();
        $date = $birthday->currentOccurrence($grace);

        if ($date !== null) {
            $base = ['date' => $date, 'next' => $birthday->occurrence($date->year + 1), 'deadline' => $date->copy()->addDays($grace)];

            if (Claim::where('user_id', $birthday->user_id)->where('year', $date->year)->exists()) {
                return ['state' => 'claimed'] + $base;
            }

            $reason = $birthday->ineligibleReason($date);

            return $reason !== null
                ? ['state' => 'blocked', 'reason' => $reason] + $base
                : ['state' => 'claimable'] + $base;
        }

        $next = $birthday->nextOccurrence();

        return [
            'state' => 'waiting',
            'date' => $next,
            'days' => (int) round(abs(today()->diffInDays($next))),
            'reason' => $birthday->ineligibleReason($next),
        ];
    }

    /**
     * Claim the birthday gift. Throws a RuntimeException with a translation key when it is not possible.
     */
    public static function claim(User $user): void
    {
        $birthday = Birthday::with('user')->where('user_id', $user->id)->first();

        if ($birthday === null) {
            throw new RuntimeException('birthday::messages.errors.no_birthday');
        }

        $date = $birthday->currentOccurrence(RewardSettings::graceDays());

        if ($date === null) {
            throw new RuntimeException('birthday::messages.errors.not_now');
        }

        $reason = $birthday->ineligibleReason($date);

        if ($reason !== null) {
            throw new RuntimeException('birthday::messages.errors.'.$reason);
        }

        try {
            DB::transaction(function () use ($user, $date) {
                // The unique index makes a second claim fail before anything is given.
                Claim::create(['user_id' => $user->id, 'year' => $date->year]);

                RewardSettings::give($user);
            });
        } catch (UniqueConstraintViolationException) {
            throw new RuntimeException('birthday::messages.errors.already_claimed');
        } catch (Throwable $e) {
            // The transaction is rolled back: nothing was claimed or given, the player can try again later.
            report($e);

            throw new RuntimeException('birthday::messages.errors.delivery');
        }
    }

    /**
     * Notify the player, once per birthday, that the gift is waiting.
     * Called at most once a day per player, when the player is seen on the website.
     */
    public static function notifyIfBirthday(User $user): void
    {
        $birthday = Birthday::with('user')->where('user_id', $user->id)->first();

        if ($birthday === null) {
            return;
        }

        $date = $birthday->currentOccurrence(RewardSettings::graceDays());

        if ($date === null
            || $birthday->notified_year === $date->year
            || $birthday->ineligibleReason($date) !== null
            || Claim::where('user_id', $user->id)->where('year', $date->year)->exists()) {
            return;
        }

        // Saved first, so the player is never notified twice for the same birthday.
        $birthday->forceFill(['notified_year' => $date->year])->saveQuietly();

        $content = $date->isToday()
            ? trans('birthday::messages.notifications.today', ['reward' => RewardSettings::name()])
            : trans('birthday::messages.notifications.waiting', [
                'reward' => RewardSettings::name(),
                'date' => format_date($date->copy()->addDays(RewardSettings::graceDays())),
            ]);

        (new AlertNotification($content))
            ->level('success')
            ->link(route('birthday.home'))
            ->send($user);
    }

    /**
     * The cache key that limits the birthday check to once a day per player.
     */
    public static function checkKey(User $user): string
    {
        return 'birthday.check.'.$user->id.'.'.today()->toDateString();
    }

    /**
     * The name of a month in the current language, translated by Carbon.
     */
    public static function monthName(int $month, bool $short = false): string
    {
        return Carbon::create(2000, $month, 1)
            ->locale(app()->getLocale())
            ->isoFormat($short ? 'MMM' : 'MMMM');
    }

    /**
     * "14 March" or "March 14", depending on the language (order and month name come from Carbon).
     */
    public static function formatDay(int $day, int $month): string
    {
        $date = Carbon::create(2000, $month, $day)->locale(app()->getLocale());

        // Carbon knows the long date format of each language (for example "D MMMM YYYY"): the year is removed from it.
        $format = (string) $date->getTranslationMessage('formats.LL');
        $format = trim((string) preg_replace('/(^Y{1,4}[^\\p{L}\\p{N}]*\\p{L}?|[\\s,.\\/-]*(?:\\[[^\\]]*\\]\\s*)?Y{1,4}(?:\\s*\\p{L}+\\.?)?$)/u', '', $format));

        if (! str_contains($format, 'D') || ! str_contains($format, 'M')) {
            $format = 'D MMMM';
        }

        return $date->isoFormat($format);
    }
}
