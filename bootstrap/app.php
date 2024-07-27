<?php

use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUser;
use App\Http\Middleware\isVerified;
use App\Http\Middleware\isVerifikator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
   )
   ->withMiddleware(function (Middleware $middleware) {
      $middleware->alias([
         'abilities' => CheckAbilities::class,
         'ability' => CheckForAnyAbility::class,
         'is_admin' => isAdmin::class,
         'is_verifikator' => isVerifikator::class,
         'is_user' => isUser::class,
         'is_verified' => isVerified::class
      ]);
   })
   ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->render(function (AuthenticationException $e, Request $request) {
         if ($request->is('api/*')) {
            return response()->json([
               'status' => false,
               'message' => 'Anda belum login.',
            ], Response::HTTP_UNAUTHORIZED);
         }
      });
   })->create();
