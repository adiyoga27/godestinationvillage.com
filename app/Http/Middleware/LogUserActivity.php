<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mencatat setiap aktivitas user yang login ke system log (activity_log, log_name = "system").
 *
 * Dipasang pada route yang membutuhkan autentikasi, sehingga apapun yang
 * dilakukan user yang login (termasuk login & logout) tercatat beserta
 * user, waktu, method, URL, route, IP, dan user agent.
 */
class LogUserActivity
{
    /**
     * Path yang tidak perlu dicatat (health check, debugbar, dsb).
     *
     * @var array<int, string>
     */
    protected array $except = [
        'up',
        '_debugbar/*',
    ];

    /**
     * Key input yang tidak boleh disimpan ke log (kredensial, token).
     *
     * @var array<int, string>
     */
    protected array $sensitive = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'token',
        '_token',
        'credit_card',
        'cvv',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Simpan user sebelum request dijalankan (untuk kasus logout,
        // setelah request user sudah tidak terautentikasi lagi).
        $userBefore = Auth::user();

        $response = $next($request);

        // Setelah request: user hasil login ikut tercatat,
        // untuk logout pakai user sebelum request.
        $user = Auth::user() ?? $userBefore;

        if ($user && ! $this->shouldExclude($request)) {
            try {
                activity('system')
                    ->causedBy($user)
                    ->withProperties($this->properties($request))
                    ->log($request->method().' '.$request->path());
            } catch (\Throwable $e) {
                // Logging tidak boleh menggagalkan request utama.
                report($e);
            }
        }

        return $response;
    }

    protected function shouldExclude(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    protected function properties(Request $request): array
    {
        return [
            'method' => $request->method(),
            'path' => $request->path(),
            'url' => $request->fullUrl(),
            'route' => optional($request->route())->getName(),
            'route_params' => $request->route()?->parameters() ?? [],
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            // Input non-GET dicatat tanpa data sensitif agar jelas
            // data apa yang dikirim (mis. id yang diubah), tanpa
            // menyimpan password/token.
            'input' => $request->isMethod('get')
                ? []
                : $request->except($this->sensitive),
        ];
    }
}
