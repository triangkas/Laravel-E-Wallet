<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class BearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $objUser = new User();
        $authorizationHeader = trim($request->header('Authorization'));
        $token = trim(substr($authorizationHeader, 7));

        if (!$authorizationHeader || substr($authorizationHeader, 0, 7) != 'Bearer ') {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        $accessToken = $objUser->where('access_token', $token)->first();
        if(!$accessToken || empty($accessToken)){
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], Response::HTTP_UNAUTHORIZED
            );
        }
         
        return $next($request);
    }
}
