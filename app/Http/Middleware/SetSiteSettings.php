<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SetSiteSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        
        if(!$request->routeIs('LaravelInstaller::*')){
            try{
                if (Schema::hasTable('settings')) {
                    foreach (Setting::all() as $setting) {
                        Config::set('settings.'.$setting->constant, $setting->value);
                    }
                }
            }catch (Exception $e) {
                return redirect()->route('LaravelInstaller::welcome');
            }
        }
        
        return $next($request);
    }
}
