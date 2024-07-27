<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
   /**
    * Handle an incoming request.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next): Response
   {
      if (!$request->user()->tokenCan('admin')) return responseError('Anda tidak memiliki akses ke modul ini.', Response::HTTP_FORBIDDEN);
      
      return $next($request);
   }
}
