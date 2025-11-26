<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Catatan: Pada Laravel 11, CSRF utama di-grup "web" ditangani oleh
     * Illuminate\Foundation\Http\Middleware\ValidateCsrfToken yang
     * dikonfigurasi via bootstrap/app.php. Kelas ini dibiarkan minimal.
     *
     * @var array<int, string>
     */
    protected $except = [];
}
