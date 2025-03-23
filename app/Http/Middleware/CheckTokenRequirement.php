<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckTokenRequirement
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the request requires a token
        if ($this->requestRequiresToken($request)) {
            try {
                // Attempt to authenticate the user via the token
                $user = JWTAuth::parseToken()->authenticate();
                if (!$user) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                // Bind the authenticated user to the request
                $request->merge(['user' => $user]);
            } catch (TokenExpiredException $e) {
                return response()->json(['error' => 'Token expired', 'token_required' => true], 401);
            } catch (TokenInvalidException $e) {
                return response()->json(['error' => 'Invalid token', 'token_required' => true], 401);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Token is required', 'token_required' => true], 401);
            }
        }

        return $next($request);
    }

    protected function requestRequiresToken(Request $request)
    {
        return $request->is('api/*'); 
    }
}
