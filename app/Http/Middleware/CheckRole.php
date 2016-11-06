<?php
/**
 * CheckRole.php
 */

namespace App\Http\Middleware;

use Closure;

/**
 * CheckRole
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = $this->getRequiredRoleForRoute($request->route());
        if ($request->user()->hasRole($role) or !$role) {
            return $next($request);
        }
        return response([
            'error' => [
                'code' => 'INSUFFICIENT_ROLE',
                'description' => 'You are not authorized'
                    .' to access this resource.',
            ]
        ], 401);
    }

    /**
     * get roles inserted in the route file
     */
    private function getRequiredRoleForRoute($route) {
        $actions = $route->getAction();
        return isset($actions['roles'])?$actions['roles']:null;
    }
}
