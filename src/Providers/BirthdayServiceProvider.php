<?php

namespace Azuriom\Plugin\Birthday\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Models\User;
use Azuriom\Plugin\Birthday\Services\BirthdayManager;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class BirthdayServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        $this->registerUserNavigation();

        Permission::registerPermissions([
            'birthday.admin' => 'birthday::admin.permissions.admin',
        ]);

        ActionLog::registerLogs([
            'birthday.settings.updated' => [
                'icon' => 'cake2',
                'color' => 'info',
                'message' => 'birthday::admin.logs.settings',
            ],
            'birthday.players.updated' => [
                'icon' => 'cake2',
                'color' => 'warning',
                'message' => 'birthday::admin.logs.player',
            ],
        ]);

        $this->notifyOnBirthday();
    }

    /**
     * Tell the players that their gift is waiting. It is done when the player is seen on the website
     * (once a day at most, thanks to the cache), so no scheduled task is needed.
     */
    protected function notifyOnBirthday(): void
    {
        Event::listen(Authenticated::class, function (Authenticated $event) {
            $user = $event->user;

            if (! $user instanceof User || ! setting('birthday.notifications', true)) {
                return;
            }

            // Cache::add only succeeds once a day per user, so the database is barely touched.
            if (Cache::add(BirthdayManager::checkKey($user), true, now()->endOfDay())) {
                rescue(fn () => BirthdayManager::notifyIfBirthday($user));
            }
        });
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'birthday.home' => trans('birthday::messages.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'birthday' => [
                'name' => trans('birthday::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-cake2',
                'route' => 'birthday.admin.*',
                'permission' => 'birthday.admin',
                'items' => [
                    'birthday.admin.players.index' => [
                        'name' => trans('birthday::admin.nav.players'),
                        'permission' => 'birthday.admin',
                    ],
                    'birthday.admin.settings' => [
                        'name' => trans('birthday::admin.nav.settings'),
                        'permission' => 'birthday.admin',
                    ],
                ],
            ],
        ];
    }

    /**
     * Return the user navigations routes to register in the user menu.
     *
     * @return array<string, array<string, string>>
     */
    protected function userNavigation(): array
    {
        if (! setting('birthday.user_menu', true)) {
            return [];
        }

        return [
            'birthday' => [
                'route' => 'birthday.home',
                'name' => trans('birthday::messages.title'),
                'icon' => 'bi bi-cake2',
            ],
        ];
    }
}
