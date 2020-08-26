<?php

namespace App\Http\Controllers\APIs;

use App\Models\City;
use App\Models\Offer;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Client;
use App\Models\Category;
use App\Models\Resturant;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function cities()
    {
        $records = City::all();

        return JsonResponse(1, 'sccuessfully', $records);
    }

    public function neighborhoods(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'city_id' => 'required|exists:cities,id'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Neighborhood::where('city_id', $request->city_id)->get();

        return JsonResponse(1, 'sccuessfully', $records);
    }

    public function listResturants(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'city_id' => 'exists:cities,id',
            'name' => 'string'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Resturant::where('name', 'LIKE', '%' . $request->filter . '%')->orWhere('city_id', $request->city_id)->paginate(20);

        return JsonResponse(1, 'successfully', $records);
    }

    public function showResturant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|exists:resturants,id'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Resturant::Find($request->resturant_id)->first();
        return JsonResponse(1, 'successfully', $records);
    }

    public function commentsAndRates(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|exists:resturants,id'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Comment::whereHas('resturant', function ($q) use ($request) {
            $q->where('resturant_id', $request->resturant_id);
        })->pluck('comments.id')->toArray();

        return JsonResponse(1, 'successfully', $records);
    }

    public function category(Request $request)
    {
        $records = Category::all();

        return JsonResponse(1, 'successfully', $records);
    }

    public function contact(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'type' => 'required|in:Complaint,Suggest,Enquiry',
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required:digits',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Contact::create($request->all());

        return JsonResponse(1, 'successfully', $records);
    }

    public function Offers()
    {
        //anas artist
        $records = Offer::where('to', '>', now())->where('from', '<', now())->paginate(20);

        if (count($records)) {
            return  JsonResponse(1, 'المتاحه انتهي', $records);
        } else {
            return  JsonResponse(1, 'العرض قد انتهي');
        }

        // dd($records->to);
    //  dd(now());

  /*  if ($records->to < now()){

        return  JsonResponse(1, 'العرض قد انتهي');
    }*/
    }

    public function newFood(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'food_image' => 'required|image|max:2048',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return responseJson(0, $validation->errors()->first(), $data);
        }

        $food = $request->user()->foods()->create($request->all());
        if ($request->hasFile('photo')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/foods/'; // upload path
            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $food->update(['photo' => 'uploads/foods/' . $name]);
        }

        return responseJson(1, 'تم الاضافة بنجاح', $food);
    }

    public function listNotifications(Request $request)
    {

        $notifications = $request->user()->notifications()->with('notifiable.neighborhood')->latest()->paginate(20);
        return JsonResponse(1, 'done loading', $notifications);
    }

}
