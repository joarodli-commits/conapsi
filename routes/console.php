<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('conapsi:admin', function () {
    \Illuminate\Support\Facades\DB::table('users')->updateOrInsert(
        ['email' => 'admin@conapsi.local'],
        [
            'name' => 'Administrador',
            'password' => \Illuminate\Support\Facades\Hash::make('Admin2026'),
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    $this->info('Usuario administrador listo: admin@conapsi.local');
})->purpose('Crea o actualiza el usuario administrador de CONAPSI');
