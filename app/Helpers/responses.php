<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('responseSuccess')) {
   function responseSuccess($data, $message): JsonResponse
   {
      return response()->json([
         'status' => true,
         'message' => $message,
         'data' => $data,
      ], Response::HTTP_OK);
   }
}

if (!function_exists('responseError')) {
   function responseError($message, $httpResponse): JsonResponse
   {
      return response()->json([
         'status' => false,
         'message' => $message,
      ], $httpResponse);
   }
}