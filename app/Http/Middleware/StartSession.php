<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;

class StartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $auth;
    protected $view;

    public function __construct(Guard $auth, Factory $view)
    {
        $this->auth = $auth;
        $this->view = $view;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->auth->user();
        return $next($request);
    }
}
