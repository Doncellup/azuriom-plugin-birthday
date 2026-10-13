<?php

namespace Azuriom\Plugin\Birthday\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $day
 * @property int $month
 * @property bool $trusted
 * @property int|null $notified_year
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Azuriom\Models\User $user
 */
class Birthday extends Model
{
    use HasTablePrefix;

    protected string $prefix = 'birthday_';

    protected $fillable = ['user_id', 'day', 'month', 'trusted', 'notified_year'];

    protected $casts = [
        'day' => 'integer',
        'month' => 'integer',
        'trusted' => 'boolean',
        'notified_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The date of the birthday in the given year. Born on February 29th: the 28th on common years.
     */
    public function occurrence(int $year): Carbon
    {
        $day = $this->day;

        if ($this->month === 2 && $day === 29 && ! checkdate(2, 29, $year)) {
            $day = 28;
        }

        return now()->setDate($year, $this->month, $day)->startOfDay();
    }

    /**
     * The birthday whose gift can be claimed today (the day itself and the grace days after it), if any.
     */
    public function currentOccurrence(int $graceDays): ?Carbon
    {
        $today = today();

        // Last year's birthday is checked too, for a grace period that crosses the new year.
        foreach ([$today->year, $today->year - 1] as $year) {
            $date = $this->occurrence($year);

            if ($date->lte($today) && $today->lte($date->copy()->addDays($graceDays))) {
                return $date;
            }
        }

        return null;
    }

    /**
     * The next birthday, today included.
     */
    public function nextOccurrence(): Carbon
    {
        $today = today();
        $date = $this->occurrence($today->year);

        return $date->lt($today) ? $this->occurrence($today->year + 1) : $date;
    }

    /**
     * Why the gift of the given birthday cannot be claimed because of the anti-abuse delays
     * ('too_late' or 'account'), or null when it can. Dates set by the staff are always valid.
     */
    public function ineligibleReason(Carbon $date): ?string
    {
        if ($this->trusted) {
            return null;
        }

        $daysBefore = max(0, (int) setting('birthday.min_days_before', 7));
        $registered = $this->created_at?->copy()->startOfDay();

        if ($registered !== null && $registered->gt($date->copy()->subDays($daysBefore))) {
            return 'too_late';
        }

        $accountDays = max(0, (int) setting('birthday.min_account_days', 7));
        $created = $this->user?->created_at?->copy()->startOfDay();

        if ($created !== null && $created->gt($date->copy()->subDays($accountDays))) {
            return 'account';
        }

        return null;
    }
}
