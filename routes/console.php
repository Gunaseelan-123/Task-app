<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('commerce:demo-report', function () {
    $this->info('Northstar Commerce demo command is ready.');
});
