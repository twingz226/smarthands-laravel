<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    // You can add cookies that should not be encrypted here:
    // protected $except = [
    //     //
    // ];
}
