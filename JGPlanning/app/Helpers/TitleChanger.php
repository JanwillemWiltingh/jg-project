<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class TitleChanger
{
    //  The base and default title
    const BASE_PATH = 'Planning';

    //  All the available routes ['Route Name' => 'Title Name']
    const ADMIN_ROUTES = ['clock' => 'Klok', 'rooster' => 'Rooster', 'users' => 'Gebruikers', 'compare' => 'Vergelijken'];
    const USER_ROUTES = ['help' => 'Help', 'profile' => 'Profiel', 'rooster' => 'Rooster', 'user' => 'Klok'];

    /**
     * Function to change the title based on the page you are on.
     * You can turn it off if you give a parameter of value False
     *
     * @param bool $is_active
     * @return string
     */
    public static function Title(bool $is_active = true): string {
        $title = self::BASE_PATH;

        if ($is_active) {
            //  Get the current route and its name
            $route = Route::getCurrentRoute();
            $name = $route->getName();

            //  Explode the name, so you can find what it belongs to
            $name_explode = explode('.', $name);

            if($name_explode[0] == 'dashboard') {
                //  if the route starts with dashboard keep the default title
                $title = self::BASE_PATH;
            } elseif ($name_explode[0] == 'admin') {
                //  Admin titles
                if (array_key_exists($name_explode[1], self::ADMIN_ROUTES)) {
                    $title = self::BASE_PATH.' - '.self::ADMIN_ROUTES[$name_explode[1]];
                } else {
                    $title = self::BASE_PATH;
                }
            } else {
                //  User titles
                if (array_key_exists($name_explode[0], self::USER_ROUTES)) {
                    $title = self::BASE_PATH.' - '.self::USER_ROUTES[$name_explode[0]];
                } else {
                    $title = self::BASE_PATH;
                }
            }
        }

        return $title;
    }
}
