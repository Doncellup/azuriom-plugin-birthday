<?php

namespace Azuriom\Plugin\Birthday\Services;

use Azuriom\Models\Server;
use Azuriom\Models\User;

/**
 * The birthday gift, as configured by the admin: website money and/or server commands.
 */
class RewardSettings
{
    public static function name(): string
    {
        $name = setting('birthday.reward_name');

        return ! empty($name) ? $name : trans('birthday::messages.reward_default');
    }

    public static function money(): float
    {
        return max(0, (float) setting('birthday.money', 0));
    }

    /**
     * @return string[]
     */
    public static function commands(): array
    {
        $commands = json_decode((string) setting('birthday.commands', '[]'), true);

        return is_array($commands) ? array_values(array_filter($commands, 'is_string')) : [];
    }

    /**
     * @return int[]
     */
    public static function serverIds(): array
    {
        $ids = json_decode((string) setting('birthday.servers', '[]'), true);

        return is_array($ids) ? array_map('intval', $ids) : [];
    }

    public static function needOnline(): bool
    {
        return (bool) setting('birthday.need_online', true);
    }

    public static function hasCommands(): bool
    {
        return ! empty(self::commands()) && ! empty(self::serverIds());
    }

    public static function graceDays(): int
    {
        return max(0, (int) setting('birthday.grace_days', 7));
    }

    /**
     * Give the gift to the user. The claim must already be recorded by the caller.
     */
    public static function give(User $user): void
    {
        $money = self::money();

        if ($money > 0) {
            $user->addMoney($money);
        }

        if (! self::hasCommands()) {
            return;
        }

        $commands = array_map(
            fn (string $command) => str_replace('{reward}', self::name(), $command),
            self::commands()
        );

        foreach (Server::executable()->whereIn('id', self::serverIds())->get() as $server) {
            $server->bridge()->sendCommands($commands, $user, self::needOnline());
        }
    }
}
