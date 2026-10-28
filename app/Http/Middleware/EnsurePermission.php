<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string ...$permissions)
    {
        $user = $request->user();
        if (!$user) {
            abort(401, 'ຕ້ອງເຂົ້າສູ່ລະບົບ');
        }
        foreach ($permissions as $p) {
            if (!$user->can($p)) {
                abort(403, 'ທ່ານບໍ່ມີສິດເຮັດການນີ້');
            }
        }
        return $next($request);
    }
}
