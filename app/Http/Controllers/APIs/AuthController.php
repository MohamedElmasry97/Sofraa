<?php

namespace App\Http\Controllers\APIs;

use App\Models\Token;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function removeToken(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required',
            'type' => 'required|in:android,ios',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }
        // $request->user()->tokens()->where('token', $request->token)->delete();
         Token::where('token', $request->token)->delete();
        return JsonResponse(1, 'تم الحذف بنجاح');
    }
}
