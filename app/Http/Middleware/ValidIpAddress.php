<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\IpManagement;
use App\Models\User;

class ValidIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = getUser();
        if (isset($user->hasWFHEmployee) && !empty($user->hasWFHEmployee) && $user->hasWFHEmployee->status == 1) {
            return $next($request);
        }
        if (getIpRestriction() == true) {
            $valid_ips = IpManagement::where('status', 1)->pluck('ip_address')->toArray();
            $user_ip = (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
            if (!empty($valid_ips) && !empty($user_ip)) {
                if (!in_array($user_ip, $valid_ips)) {
                    Auth::logout();
                    abort(401);
                } else {
                    return $next($request);
                }
            } else {
                abort(401);
            }
        } else {
            return $next($request); 
        }
    }
}
