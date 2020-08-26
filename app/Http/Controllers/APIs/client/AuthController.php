<?php

namespace App\Http\Controllers\APIs\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerClient(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|confirmed|min:6|max:20',
            'phone' => 'required|unique:clients,phone|numeric',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'required | exists:neighborhoodS,id',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());

        $client->api_token = Str::random(60);

        $client->save();

        return JsonResponse(1, 'sccussfull adding client', [
            'api_token' => $client->api_token,
            'client' => $client,
        ]);
    }

    public function loginClient(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6|max:20',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        //return Auth::guard('api')->validate($request->all());
        $client = Client::where('email', $request->email)->first();

        if ($client) {
            if (Hash::check($request->password, $client->password)) {
                return JsonResponse(1, 'sccussfully login', [
                    'api_token' => $client->api_token,
                    'client' => $client,
                ]);
            } else {
                return JsonResponse(0, 'invalid input');
            }
        } else {
            return JsonResponse(0, 'invalid input ');
        }
        //    return Auth::guard('api')->attempt([
        //        'phone' => $request->phone,
        //        'password' => $request->password,
        //    ]);
    }

    public function resetPasswordClient(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|exists:clients,phone',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $client = Client::where('phone', $request->phone)->first();

        $code = rand(11111, 99999);
        $update = $client->update(['pin_code' => $code]);
        if ($update) {
            // SMS_Verification($request->phone, 'Your reset Password Code is :' . $code);

              Mail::to($client->email)->send(new Reset_Password($code));

            return JsonResponse(1, ' this is coode for test  ', ['test_code' => $code]);
        } else {
            return JsonResponse(0, 'invalid input');
        }
    }

    public function setNewPasswordClient(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|exists:clients,phone',
            'password' => 'required|confirmed',
            'pin_code' => 'required',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $client = Client::where('phone', $request->phone)->first();
        if ($client->pin_code == $request->pin_code) {
            $request->merge(['password' => bcrypt($request->password)]);
            $update = $client->update([
                'password' => $request->password,
                'pin_code' => $request->NULL,
            ]);
            if ($update) {
                return JsonResponse(1, 'password changed');
            } else {
                return JsonResponse(0, 'password not changed');
            }
        } else {
            return JsonResponse(0, 'pin_code not correct');
        }
    }

    public function registerTokenClient(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required|unique:tokens,token',
            'type' => 'required|in:android,ios',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }
        Token::where('token', $request->token)->delete();
        $request->user('client')->tokens()->create($request->all());
        return JsonResponse(1, 'successfully add');
    }

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

    public function editClient(Request $request)
    {
        //dd($request->user()->id);
        $validation = validator()->make($request->all(), [
            'password' => 'confirmed',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0, $validation->errors()->first(), $data);
        }

        if ($request->has('name')) {
            $request->user()->update($request->only('name'));
        }
        if ($request->has('email')) {
            $request->user()->update($request->only('email'));
        }
        if ($request->has('password')) {
            $request->merge(['password' => bcrypt($request->password)]);
            $request->user()->update($request->only('password'));
        }

        if ($request->has('phone')) {
            $request->user()->update($request->only('phone'));
        }

        if ($request->has('neighborhood_id')) {
            $request->user()->update($request->only('neighborhood_id'));
        }

        if ($request->has('city_id')) {
            $request->user()->update($request->only('city_id'));
        }

        $loginUser = $request->user();
        $loginUser->update($request->all());

        $loginUser->save();

        $data = [
            'client' => $request->user()->load('neighborhoods')
        ];
        return JsonResponse(1, 'successfully edit client', $data);
    }
}
