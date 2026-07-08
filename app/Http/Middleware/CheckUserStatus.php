<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->session()->has('status_checked')) {
            $request->session()->flash('success', 'ようこそ、初回ログインありがとうございます！');
            $request->session()->put('status_checked', true);
        }

        return $next($request);
    }
}
