<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemMaintenanceController extends Controller
{
    public function runMigration(Request $request, string $key)
    {
        $secretKey = env('PROGRAMMER_MIGRATION_KEY');

        if (!$secretKey || $key !== $secretKey) {
            abort(403, 'SECRET KEY SALAH.');
        }

        try {
            Artisan::call('migrate', [
                '--force' => true,
            ]);

            return response(
                '<h3>Migration berhasil dijalankan</h3><pre>' . e(Artisan::output()) . '</pre>'
            );
        } catch (\Throwable $e) {
            return response(
                '<h3>Migration gagal</h3><pre>' . e($e->getMessage()) . '</pre>',
                500
            );
        }
    }
}
