<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
   public function login(LoginRequest $request): JsonResponse
   {
      try {
         $credentials = $request->validated();
         if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token', [$user->role_name])->plainTextToken;
            $user->token = $token;
            $user->token_type = 'Bearer';

            return responseSuccess($user, 'Login berhasil');
         }

         return responseError('Email atau Password salah.', Response::HTTP_NOT_ACCEPTABLE);

      } catch (\Throwable $th) {
         return responseError('Gagal melakukan login, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function register(RegisterRequest $request): JsonResponse
   {
      try {
         $data = $request->validated();
         $data['password'] = Hash::make($data['password']);
         $data['role'] = '0';
         $data['is_verify'] = 0;

         $user = User::create($data);
         return responseSuccess($user, 'Registrasi berhasil');
      } catch (\Throwable $th) {
         return responseError('Gagal melakukan pendaftaran, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function logout(): JsonResponse
   {
      try {
         auth()->user()->tokens()->delete();
         
         return response()->json([
            'status' => true,
            'message' => 'Logout berhasil.'
         ], Response::HTTP_OK);

      } catch (\Throwable $th) {
         return responseError('Gagal melakukan logout, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }
}
