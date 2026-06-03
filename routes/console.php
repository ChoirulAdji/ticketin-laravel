<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal reminder H-1 event — jalankan setiap hari jam 08:00 WIB
Schedule::command('ticketin:send-reminders')->dailyAt('08:00')->timezone('Asia/Jakarta');
