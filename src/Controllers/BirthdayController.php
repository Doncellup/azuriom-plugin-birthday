<?php

namespace Azuriom\Plugin\Birthday\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Birthday\Models\Birthday;
use Azuriom\Plugin\Birthday\Services\BirthdayManager;
use Azuriom\Plugin\Birthday\Services\RewardSettings;
use Illuminate\Http\Request;
use RuntimeException;

class BirthdayController extends Controller
{
    /**
     * Show the birthday page of the player.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $birthday = $user !== null ? Birthday::with('user')->where('user_id', $user->id)->first() : null;

        return view('birthday::index', [
            'birthday' => $birthday,
            'status' => $user !== null ? BirthdayManager::status($birthday) : null,
            'rewardName' => RewardSettings::name(),
            'rewardMoney' => RewardSettings::money(),
            'rewardHasCommands' => RewardSettings::hasCommands(),
            'graceDays' => RewardSettings::graceDays(),
            'daysBefore' => max(0, (int) setting('birthday.min_days_before', 7)),
            'accountDays' => max(0, (int) setting('birthday.min_account_days', 7)),
            'welcome' => setting('birthday.welcome'),
        ]);
    }

    /**
     * Save the birthday of the player (only once).
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'day' => ['required', 'integer', 'between:1,31'],
            'month' => ['required', 'integer', 'between:1,12'],
        ]);

        try {
            BirthdayManager::register($request->user(), (int) $validated['day'], (int) $validated['month']);
        } catch (RuntimeException $e) {
            return to_route('birthday.home')->with('error', trans($e->getMessage()));
        }

        return to_route('birthday.home')->with('success', trans('birthday::messages.saved'));
    }

    /**
     * Claim the gift of the current birthday.
     */
    public function claim(Request $request)
    {
        try {
            BirthdayManager::claim($request->user());
        } catch (RuntimeException $e) {
            return to_route('birthday.home')->with('error', trans($e->getMessage()));
        }

        return to_route('birthday.home')
            ->with('success', trans('birthday::messages.claimed', ['reward' => RewardSettings::name()]))
            ->with('birthday.celebrate', true);
    }
}
