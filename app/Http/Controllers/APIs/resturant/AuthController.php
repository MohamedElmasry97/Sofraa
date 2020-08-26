<?php

namespace App\Http\Controllers\APIs\resturant;

use App\Models\Token;
use App\Models\Resturant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:resturants,email',
            'password' => 'required|confirmed|min:6|max:20',
            'phone' => 'required|unique:resturants,phone|numeric',
            'communication_phone' => 'unique:resturants,communication_phone|numeric',
            'whats_up' => 'unique:resturants,whats_up|numeric',
            'minmum_order' => 'required:digits',
            'delivery_fee' => 'required:digits',
            'city_id' => 'required|exists:cities,id',
            'neighborhood_id' => 'required | exists:neighborhoodS,id',
            'resturant_image' => 'mimes:jpeg,bmp,png|image'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $request->merge(['password' => bcrypt($request->password)]);
        $resturant = Resturant::create($request->all());

        $resturant->api_token = Str::random(60);

        $resturant->save();

        return JsonResponse(1, 'sccussfull adding resturant', [
            'api_token' => $resturant->api_token,
            'client' => $resturant,
        ]);
    }

    public function loginResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6|max:20',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        //return Auth::guard('api')->validate($request->all());
        $resturant = Resturant::where('email', $request->email)->first();

        if ($resturant) {
            if (Hash::check($request->password, $resturant->password)) {
                return JsonResponse(1, 'sccussfully login', [
                    'api_token' => $resturant->api_token,
                    'client' => $resturant,
                ]);
            } else {
                return JsonResponse(0, 'invalid login ');
            }
        } else {
            return JsonResponse(0, 'successfully login ');
        }
        //    return Auth::guard('api')->attempt([
        //        'phone' => $request->phone,
        //        'password' => $request->password,
        //    ]);
    }

    public function resetPasswordResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|exists:clients,phone',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $resturant = Resturant::where('phone', $request->phone)->first();

        $code = rand(11111, 99999);
        $update = $resturant->update(['pin_code' => $code]);
        if ($update) {
            // SMS_Verification($request->phone, 'Your reset Password Code is :' . $code);

            //  Mail::to($resturant->email)->send(new Reset_Password($code));

            return JsonResponse(1, ' test reset password ', ['test_code' => $code]);
        } else {
            return JsonResponse(0, 'invalid input');
        }
    }

    public function setNewPasswordResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|exists:clients,phone',
            'password' => 'required|confirmed',
            'pin_code' => 'required',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $resturant = Resturant::where('phone', $request->phone)->first();
        if ($resturant->pin_code == $request->pin_code) {
            $request->merge(['password' => bcrypt($request->password)]);
            $update = $resturant->update([
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

    public function registerTokenResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required|unique:tokens,token',
            'type' => 'required|in:android,ios',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        Token::where('token', $request->token)->delete();
        $request->user('resturant')->tokens()->create($request->all());
        return JsonResponse(1, 'successfull add');
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
        if ($request->has('status')) {
            $request->user()->update($request->only('status'));
        }

        $loginUser = $request->user();
        $loginUser->update($request->all());
        if ($request->hasFile('resturant_image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/foods/'; // upload path
            $photo = $request->file('resturant_image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $loginUser->update(['resturant_image' => 'uploads/foods/' . $name]);
        }
        $loginUser->save();

        $data = [
            'resturant' => $request->user()->load('neighborhood')
        ];
        return JsonResponse(1, 'successfully edit client', $data);
    }
}
