<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SubmitPermissionRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
   public function listPermission(): JsonResponse
   {
      try {
         $permissions = Permission::where('user_id', auth()->user()->id)->orderBy('status', 'ASC')->get();
         return responseSuccess($permissions, 'List data pengajuan izin');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function showPermission(Permission $permission): JsonResponse
   {
      try {
         return responseSuccess($permission, 'Detail pengajuan izin');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function submitPermission(SubmitPermissionRequest $request): JsonResponse
   {
      try {
         $data = $request->validated();
         $data['user_id'] = auth()->user()->id;
         $data['status'] = '0';
         
         $permission = Permission::create($data);
         return responseSuccess($permission, 'Pengajuan izin berhasil diajukan.');
      } catch (\Throwable $th) {
         return responseError('Pengajuan izin gagal, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function updatePermission(Permission $permission, SubmitPermissionRequest $request): JsonResponse
   {
      try {
         $data = $request->validated();
         $permission->update($data);
         return responseSuccess($permission, 'Pengajuan izin berhasil diubah.');
      } catch (\Throwable $th) {
         return responseError('Gagal mengubah pengajuan izin, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function cancelPermission(Permission $permission): JsonResponse
   {
      try {
         $permission->update(['status' => '4']);
         return responseSuccess($permission, 'Pengajuan izin berhasil dibatalkan.');
      } catch (\Throwable $th) {
         return responseError('Gagal membatalkan pengajuan izin, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function deletePermission(Permission $permission): JsonResponse
   {
      try {
         $permission->delete();
         return response()->json([
            'status' => true,
            'message' => 'Pengajuan izin berhasil dihapus.'
         ], Response::HTTP_OK);
      } catch (\Throwable $th) {
         return responseError('Gagal menghapus pengajuan izin, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function resetPassword(ResetPasswordRequest $request): JsonResponse
   {
      try {
         DB::transaction(function () use ($request, &$user) {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user = auth()->user();
            $user->update($data);
            $user->tokens()->delete();
         });

         return responseSuccess($user, 'Reset password pengguna berhasil');
      } catch (\Throwable $th) {
         return responseError('Gagal me-reset password, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }
}
