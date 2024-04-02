<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EPayment
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $envWhiteList = config('e_payment_api.white_list_ips');

        if(!empty($envWhiteList)) {
            $whiteListIps = explode(';', $envWhiteList);
        }

        if (empty($whiteListIps) || !in_array($request->getClientIp(), $whiteListIps)) {
            abort(403, "You are restricted to access the site.");
        }
        return $next($request);
    }
}
