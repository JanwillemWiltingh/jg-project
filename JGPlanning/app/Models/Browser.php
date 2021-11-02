<?php

namespace App\Models;

use App\Helpers\BrowserDetection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Browser extends Model
{
    use HasFactory;

    /**
     * Function the get the browser name of the user
     *
     * @return string
     */
    public static function getBrowserName(): string {
        $browser = new BrowserDetection();
        return $browser->getName();
    }
}
