<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
                Menu::make('Users')
                    ->icon('people')
                    ->title('Пользователи')
                    ->route('platform.systems.users')
                    ->permission('platform.systems.users'),

            Menu::make('Roles')
                ->icon('lock')
                ->title('Роли')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')

        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('Система'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.admin.access', 'Access Admin'),
            ItemPermission::group(__('Ресурсы'))
                ->addPermission('platform.resource.edit', 'Редактура')
                ->addPermission('platform.resource.destroy', 'Удаление')
                ->addPermission('platform.resource.add', 'Создание')
        ];
    }
}
