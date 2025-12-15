<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Main;
use App\Helpers\QueryAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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

        $totalPhysicalCollectionReject = QueryAPI::get("
            select
                count(letter_detail_id) as total
            from
                letter_detail
            where
                penerbit_id = $id and
                qty_reject > 0 and
                (qty_accept = 0 or qty_accept is null) and
                (qty_hibah = 0 or qty_hibah is null)
        ", true);

        Config::set('system.collection_reject', $totalPhysicalCollectionReject->TOTAL ?? 0);

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
