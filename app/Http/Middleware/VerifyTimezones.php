<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyTimezones
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verify database connection is using UTC
        $dbTimezone = DB::select("SELECT @@session.time_zone")[0]->{'@@session.time_zone'};
        if ($dbTimezone !== '+00:00') {
            DB::statement("SET time_zone='+00:00'");
        }
        return $next($request);
    }
}
