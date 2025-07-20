<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google reCAPTCHA v2 integration
    |
    */

    'website_key' => env('GOOGLE_RECAPTCHA_WEBSITE_KEY'),
    'secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
];