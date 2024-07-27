<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
   public function listUser(): JsonResponse
   {
      try {
         $users = User::all();
         return responseSuccess($users, 'List data pengguna');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function listPermission(): JsonResponse
   {
      try {
         $permissions = Permission::with('user')->latest()->get();
         return responseSuccess($permissions, 'List data pengajuan izin');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function addVerifikator(RegisterRequest $request): JsonResponse
   {
      try {
         $data = $request->validated();
         $data['password'] = Hash::make($data['password']);
         $data['role'] = '2';
         $data['is_verify'] = 1;

         $user = User::create($data);
         return responseSuccess($user, 'Berhasil menambahkan verifikator');
      } catch (\Throwable $th) {
         return responseError('Gagal menambahkan data, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function setAsVerifikator(User $user): JsonResponse
   {
      try {
         DB::transaction(function () use ($user) {
            $user->update(['role' => '2', 'is_verify' => 1]);
            $user->tokens()->delete();
         });

         return responseSuccess($user, 'Pengguna berhasil diubah menjadi verifikator');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }


   public function resetPasswordUser(User $user, ResetPasswordRequest $request): JsonResponse
   {
      try {
         DB::transaction(function () use ($user, $request) {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user->update($data);
            $user->tokens()->delete();
         });

         return responseSuccess($user, 'Reset password pengguna berhasil');
      } catch (\Throwable $th) {
         return responseError('Gagal me-reset password, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }
}
