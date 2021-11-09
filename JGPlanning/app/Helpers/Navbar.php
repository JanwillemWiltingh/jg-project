<?php
namespace App\Helpers;

use App\Models\Role;
use Illuminate\Support\Collection;

class Navbar
{
    public const NAV_ITEMS = [
        'dashboard' => [
            'type' => 'item',
            'tab-item' => false,
            'tabs' => [],
            'active_url' => ['/'],
            'href' => 'dashboard.home',
            'icon' => ['type' => 'material', 'name' => 'dashboard'],
            'name' => 'Dashboard',
            'can' => null,
            'users' => ['employee', 'admin', 'maintainer'],
        ],
        'employee-clock' => [
            'type' => 'item',
            'tab-item' => false,
            'tabs' => [],
            'active_url' => ['gebruiker/clock', 'gebruiker/clock/*'],
            'href' => 'user.clock.index',
            'icon' => ['type' => 'fa', 'name' => 'clock'],
            'name' => 'Klok',
            'can' => 'employee-clock',
            'users' => ['employee'],
        ],
        'employee-rooster-tab' => [
            'type' => 'tab',
            'tab-item' => false,
            'tabs' => ['employee-rooster'],
            'active_url' => [],
            'href' => null,
            'icon' => ['type' => 'fa', 'name' => 'clock'],
            'name' => 'Tijden',
            'can' => 'employee-rooster',
            'users' => ['employee'],
        ],
        'employee-rooster' => [
            'type' => 'item',
            'tab-item' => true,
            'tabs' => [],
            'active_url' => ['rooster', 'rooster/*'],
            'href' => 'rooster.index',
            'icon' => ['type' => 'fa', 'name' => 'calender'],
            'name' => 'Rooster',
            'can' => null,
            'users' => ['employee'],
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
    }
}

