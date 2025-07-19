<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    public function handle($request, \Closure $next, ...$guards)
    {
        Log::info('Authenticate middleware triggered');
        return parent::handle($request, $next, ...$guards);
    }
    
    protected function redirectTo($request): ?string
    {
        
        if (!$request->expectsJson()) {
            abort(response()->json(['message' => 'No autenticado.'], 401));
        }

        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new \Illuminate\Auth\AuthenticationException(
            'No autenticado',
            $guards,
            $this->redirectTo($request)
        );
    }
}