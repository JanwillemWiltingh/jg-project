<?php
namespace App\Helpers;

use App\Models\Role;
use Illuminate\Support\Collection;

class Navbar
{
    public const NAV_ITEMS = [
        'dashboard' => [
            'active_url' => ['/'],
            'href' => 'dashboard.home',
            'icon' => ['type' => 'material-icons', 'name' => 'dashboard'],
            'name' => 'Dashboard',
            'can' => null,
            'users' => ['employee', 'admin', 'maintainer'],
        ],
        'employee-clock' => [
            'active_url' => ['gebruiker/clock', 'gebruiker/clock/*'],
            'href' => 'user.clock.index',
            'icon' => ['type' => 'fa', 'name' => 'fa-clock'],
            'name' => 'Klok',
            'can' => 'employee-clock',
            'users' => ['employee'],
        ],
        'employee-rooster' => [
            'active_url' => ['rooster', 'rooster/*'],
            'href' => 'rooster.index',
            'icon' => ['type' => 'fa', 'name' => 'fa-calendar'],
            'name' => 'Rooster',
            'can' => 'employee-rooster',
            'users' => ['employee'],
        ],

        'admin-clocker' => [
            'active_url' => ['admin/clock', 'admin/clock/*'],
            'href' => 'admin.clock.index',
            'icon' => ['type' => 'fa', 'name' => 'fa-clock'],
            'name' => 'Klok',
            'can' => 'admin-clock',
            'users' => ['admin', 'maintainer'],
        ],
        'admin-beschikbaarheid-1' => [
            'active_url' => ['rooster', 'rooster/*'],
            'href' => 'admin.rooster.index',
            'icon' => ['type' => 'fa', 'name' => 'fa-calendar'],
            'name' => 'Rooster',
            'can' => 'admin-beschikbaarheid',
            'users' => ['admin', 'maintainer'],
        ],
        'admin-beschikbaarheid-2' => [
            'active_url' => ['admin/vergelijken', 'admin/vergelijken/*'],
            'href' => 'admin.compare.index',
            'icon' => ['type' => 'fa', 'name' => 'fa-calendar'],
            'name' => 'Vergelijken',
            'can' => 'admin-beschikbaarheid',
            'users' => ['admin', 'maintainer'],
        ],
    ];

    public static function getNavItems($user): Collection
    {
        $role = Role::find($user['role_id']);

        $items = collect();
        foreach(self::NAV_ITEMS as $item) {
            if(in_array($role['name'], $item['users'])) {
                $items->push(collect($item));
            }
        }

        return $items;
        return collect();
    }
}

