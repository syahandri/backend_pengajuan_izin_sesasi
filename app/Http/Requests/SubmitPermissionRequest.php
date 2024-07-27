<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubmitPermissionRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return true;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      return [
         'title' => 'required|string',
         'details' => 'required',
         'date' => 'required|date'
      ];
   }

   public function attributes(): array
   {
      return [
         'title' => 'judul pengajuan izin',
         'details' => 'detail pengajuan izin',
         'date' => 'tanggal pengajuan'
      ];
   }

   public function messages(): array
   {
      return [
         'required' => ':attribute harus diisi.',
         'string' => ':attribute harus berupa string.',
         'date' => ':attribute tidak valid.'
      ];
   }

   protected function failedValidation(Validator $validator)
   {
      throw new HttpResponseException(response()->json([
         'status' => false,
         'errors' => $validator->errors()
      ], Response::HTTP_UNPROCESSABLE_ENTITY));
   }
}
