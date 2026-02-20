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

        if (in_array($status, [1, 3, null, ''])) {
            return redirect('auth/not-verified');
        }

        $totalPhysicalCollectionReject = QueryAPI::get("
            select
                count(letter_detail.letter_detail_id) as total
            from
                letter_detail
            join
                letter on letter.letter_id = letter_detail.letter_id
            where
                letter.penerbit_id = $id and
                letter_detail.qty_reject > 0 and
                (letter_detail.qty_hibah = 0 or letter_detail.qty_hibah is null) and
                (letter_detail.qty_retur = 0 or letter_detail.qty_retur is null)
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
