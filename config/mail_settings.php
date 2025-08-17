<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail Configuration Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the mail functionality of the application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Mail Enabled
    |--------------------------------------------------------------------------
    |
    | This option determines whether the application should send emails.
    | Set this to false to disable all email sending.
    |
    */
    'enabled' => env('MAIL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    |
    | The email address where admin notifications should be sent.
    |
    */
    'admin_email' => env('MAIL_ADMIN_EMAIL', 'admin@example.com'),
]; 