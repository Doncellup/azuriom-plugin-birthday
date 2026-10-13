<?php

namespace Azuriom\Plugin\Birthday\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Server;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Birthday\Services\RewardSettings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        return view('birthday::admin.settings', [
            'rewardName' => setting('birthday.reward_name'),
            'money' => RewardSettings::money(),
            'commands' => RewardSettings::commands(),
            'serverIds' => RewardSettings::serverIds(),
            'servers' => Server::executable()->get(),
            'needOnline' => RewardSettings::needOnline(),
            'graceDays' => RewardSettings::graceDays(),
            'daysBefore' => max(0, (int) setting('birthday.min_days_before', 7)),
            'accountDays' => max(0, (int) setting('birthday.min_account_days', 7)),
            'notifications' => (bool) setting('birthday.notifications', true),
            'userMenu' => (bool) setting('birthday.user_menu', true),
            'welcome' => setting('birthday.welcome'),
        ]);
    }

    public function save(Request $request)
    {
        $validated = $this->validate($request, [
            'reward_name' => ['nullable', 'string', 'max:100'],
            'money' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'commands' => ['sometimes', 'nullable', 'array'],
            'commands.*' => ['nullable', 'string', 'max:1000'],
            'servers' => ['sometimes', 'nullable', 'array'],
            'servers.*' => ['integer', 'exists:servers,id'],
            'grace_days' => ['required', 'integer', 'min:0', 'max:60'],
            'min_days_before' => ['required', 'integer', 'min:0', 'max:365'],
            'min_account_days' => ['required', 'integer', 'min:0', 'max:3650'],
            'welcome' => ['nullable', 'string', 'max:2000'],
        ]);

        Setting::updateSettings([
            'birthday.reward_name' => $validated['reward_name'] ?? null,
            'birthday.money' => $validated['money'] ?? 0,
            'birthday.commands' => json_encode(array_values(array_filter($validated['commands'] ?? []))),
            'birthday.servers' => json_encode(array_map('intval', $validated['servers'] ?? [])),
            'birthday.need_online' => $request->has('need_online') ? '1' : '0',
            'birthday.grace_days' => $validated['grace_days'],
            'birthday.min_days_before' => $validated['min_days_before'],
            'birthday.min_account_days' => $validated['min_account_days'],
            'birthday.notifications' => $request->has('notifications') ? '1' : '0',
            'birthday.user_menu' => $request->has('user_menu') ? '1' : '0',
            'birthday.welcome' => $validated['welcome'] ?? null,
        ]);

        ActionLog::log('birthday.settings.updated');

        return to_route('birthday.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
