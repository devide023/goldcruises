<?php

namespace App\Http\Middleware;

use App\Code\Utils;
use App\Http\Controllers\Api\UserController;
use Closure;

class UserRoute
{
    use Utils;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $root = strtolower($request->root());
        $url = strtolower($request->url());
        $routes = $this->get_current_user_route();
        var_dump($routes);
        $currenturl = str_replace($root . '/', '', $url);
        $cnt = collect($routes)->where('route', $currenturl)->count();
        if ($cnt > 0)
        {
            return $next($request);
        } else
        {
            return response()->json([
                'code' => 0,
                'msg'  => $url . '接口无权限,请联系管理员'
            ]);
        }
    }
}
