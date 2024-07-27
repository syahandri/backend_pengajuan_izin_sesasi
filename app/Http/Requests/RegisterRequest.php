<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
         'name' => 'required|string',
         'email' => 'required|email|unique:users',
         'password' => 'required|min:8|confirmed'
      ];
   }

   public function attributes(): array
   {
      return [
         'name' => 'nama',
      ];
   }

   public function messages(): array
   {
      return [
         'required' => ':attribute harus diisi.',
         'email' => ':attribute tidak valid.',
         'string' => ':attribute harus berupa string.',
         'unique' => ':attribute sudah digunakan.',
         'min' => ':attribute harus minimal :min karakter',
         'confirmed' => 'konfirmasi :attribute tidak sesuai',
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
