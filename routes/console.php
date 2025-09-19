<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Define a custom Artisan command "php artisan inspire"
Artisan::command('inspire', function () {
    // When executed, it will output a random inspiring quote
    $this->comment(Inspiring::quote());
})
// Set a short description for "php artisan list"
    ->purpose('Display an inspiring quote');
