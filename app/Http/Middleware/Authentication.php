<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Authentication
{
    /**
     * except
     *
     * @var array
     */
    protected $except = [
        'auth/not-verified',
        'auth/logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldSkipVerification($request)) {
            return $next($request);
        }

        $id = Session::get('id');
        $status = Session::get('status');

        if (!$id) {
            Session::flush();

            return redirect('/');
        }

        if (in_array($status, [1, 2])) {
            return redirect('auth/not-verified');
        }

        return $next($request);
    }

    /**
     * shouldSkipVerification
     *
     * @param  mixed $request
     * @return bool
     */
    protected function shouldSkipVerification(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
