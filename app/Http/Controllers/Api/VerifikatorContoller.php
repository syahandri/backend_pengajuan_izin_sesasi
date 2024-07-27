<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VerifikatorContoller extends Controller
{
   public function listUser(Request $request): JsonResponse
   {
      try {
         $users = User::filter($request->verify)->where('role', '0')->get();
         return responseSuccess($users, 'List data pengguna');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function listPermission(Request $request): JsonResponse
   {
      try {
         $permissions = Permission::with('user')->status($request->status)->orderBy('status', 'ASC')->get();
         return responseSuccess($permissions, 'List data pengajuan izin');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function verifyUser(User $user): JsonResponse
   {
      try {
         $user->update(['is_verify' => 1]);
         return responseSuccess($user, 'Pengguna berhasil diverifikasi');
      } catch (\Throwable $th) {
         return responseError('Gagal memverifikasi pengguna, silahkan coba beberapa saat lagi.', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function accPermission(Permission $permission, Request $request): JsonResponse
   {
      try {
         $validator = Validator::make($request->all(), [
            'notes' => 'required'
         ], ['required' => 'komentar harus diisi.']);

         if ($validator->fails()) {
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
         }

         $data = $validator->validated();
         $permission->update(['status' => '1', 'notes' => $data['notes']]);
         return responseSuccess($permission, 'ACC data berhasil.');
      } catch (\Throwable $th) {
         return responseError('Terjadi kasalahan, silahkan coba beberapa saat lagi,', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function revisionPermission(Permission $permission, Request $request): JsonResponse
   {
      try {
         $validator = Validator::make($request->all(), [
            'notes' => 'required'
         ], ['required' => 'komentar harus diisi.']);

         if ($validator->fails()) {
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
         }

         $data = $validator->validated();
         $permission->update(['status' => '2', 'notes' => $data['notes']]);
         return responseSuccess($permission, 'Revisi data berhasil.');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan , silahkan coba beberapa saat lagi,', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }

   public function rejectPermission(Permission $permission, Request $request): JsonResponse
   {
      try {
         $validator = Validator::make($request->all(), [
            'notes' => 'required'
         ], ['required' => 'komentar harus diisi.']);

         if ($validator->fails()) {
            return response()->json([
               'status' => false,
               'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
         }

         $data = $validator->validated();
         $permission->update(['status' => '3', 'notes' => $data['notes']]);
         return responseSuccess($permission, 'Tolak data berhasil.');
      } catch (\Throwable $th) {
         return responseError('Terjadi kesalahan, silahkan coba beberapa saat lagi,', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
   }
}
