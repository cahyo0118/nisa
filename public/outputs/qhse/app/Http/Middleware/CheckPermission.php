<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use App\Permission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        $permissions = explode('|', $permissions);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $my_roles = Auth::user()->roles;

        foreach ($permissions as $permission) {
            $p = Permission::where('name', $permission)->first();

            if (empty($p)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Permission not found'
                ], 400);
            }

            foreach ($my_roles as $role) {
                $is_granted = DB::select('select * from role_permission where permission_id = ? AND role_id = ?', [$p->id, $role->id]);

                if (!empty($is_granted)) {
                    return $next($request);
                }
            }
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Permission Denied'
        ], 403);
    }
}
