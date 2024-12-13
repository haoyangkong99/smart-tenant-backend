<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateMiddleware
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
        try {
            // Attempt to authenticate the user using the API guard
            if (!Auth::guard('api')->attempt($request->only('email', 'password'))) {
                throw new ValidationException($request, [
                    'message' => 'Invalid Credentials'
                ]);
            }
            else
            {
                return $next($request);
            }


        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred'
            ], 500);
        }
    }
}