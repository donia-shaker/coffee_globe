<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:monitor', function () {
    try {
        DB::connection()->getPdo();
        return 0;
    } catch (\Exception $e) {
        return 1;
    }
})->purpose('Check database connection');
