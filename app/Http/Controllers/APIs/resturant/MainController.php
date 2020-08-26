<?php

namespace App\Http\Controllers\APIs\resturant;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function createOffers(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'cotent' => 'required',
            'price_with_offer' => 'required:digits',
            'from' => 'required',
            'to' => 'required',
            'food_id' => 'required|exists:foods,id'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = $request->user('resturant')->offers()->create($request->all());

        return JsonResponse(1, 'successfully', $records);
    }

    public function listResturantFood(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|exists:resturants,id'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Food::whereHas('resturants', function ($q) use ($request) {
            $q->where('resturant_id', $request->resturant_id);
        })->paginate(20);
        return JsonResponse(1, 'successfully', $records);
    }

    public function newFood(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'description' => 'required|string',
            'price' => 'required:digits',
            'timeReady' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = $request->user('resturant')->foods()->create($request->all());

        if ($request->hasFile('food_image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/foods/'; // upload path
            $photo = $request->file('food_image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $records->update(['food_image' => 'uploads/foods/' . $name]);
        }

        return JsonResponse(1, 'successfully', $records);
    }

    public function updateFood(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'timeReady' => 'required',
            'food_image' => 'image|max:2048',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return JsonResponse(0, $validation->errors()->first(), $data);
        }

        $food = $request->user()->items()->find($request->item_id);
        if (!$food) {
            return JsonResponse(0, 'not have foods yet');
        }
        $food->update($request->all());
        if ($request->hasFile('food_image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/foods/'; // upload path
            $photo = $request->file('food_image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $food->update(['food_image' => 'uploads/foods/' . $name]);
        }

        return JsonResponse(1, 'successfully update', $food);
    }

    public function deleteFood(Request $request)
    {
        $food = $request->user()->foods()->find($request->food_id);

        if (!$food) {
            return JsonResponse(0, 'no data found');
        } else {
            $request->user()->foods()->detach($request->food_id);
            $food->delete();
            return JsonResponse(1, 'deleted successfully');
        }
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->orders()->where(function ($order) use ($request) {
            if ($request->has('status') && $request->status == 'completed') {
                $order->where('status', '!=', 'pending');
            } elseif ($request->has('status') && $request->status == 'current') {
                $order->where('status', '=', 'accepted');
            } elseif ($request->has('status') && $request->status == 'pending') {
                $order->where('status', '=', 'pending');
            }
        })->with('clients', 'foods', 'resturant.neighborhood', 'resturant.categories')->latest()->paginate(20);
        return JsonResponse(1, 'successfully', $orders);
    }

    public function showOrder(Request $request)
    {
        $order = Order::with('foods', 'clients', 'resturant.neighborhood', 'resturant.categories')->find($request->order_id);
        return JsonResponse(1, 'successfully', $order);
    }

    public function acceptOrder(Request $request)
    {
        $order = $request->user()->orders()->find($request->order_id);
        if (!$order) {
            return JsonResponse(0, 'not orders found');
        }
        if ($order->state == 'accepted') {
            return JsonResponse(1, 'order accepted');
        }
        $order->update(['status' => 'accepted']);
        $resturant = $order->resturant;
        $notification = $resturant->notifications()->create([
            'title' => 'Your order is accepted',
            'content' => 'Order no. ' . $request->order_id . ' is accepted',
            'date' => now()
        ]);

        $tokens = $resturant->tokens()->where('token', '!=', '')->pluck('token')->toArray();
        if (count($tokens)) {
            public_path();
            $title = $notification->title;
            $content = $notification->content;
            $data = [
                'order_id' => $order->id,
                'user_type' => 'resturant',
            ];
            $send = notifyByFirebase($title, $content, $tokens, $data);
        }
        $data = [
            'order' => $order->fresh()->load('foods'),
            'notifications' => $send
        ];

        return JsonResponse(1, 'order has been accepted', $data);
    }

    public function rejectOrder(Request $request)
    {
        $order = $request->user()->orders()->find($request->order_id);
        if (!$order) {
            return JsonResponse(0, 'not order found');
        }
        if ($order->state == 'rejected') {
            return JsonResponse(0, 'this order had been rejected before');
        }
        $order->update(['status' => 'rejected']);
        $resturant = $order->resturant;
        $notification = $resturant->notifications()->create([
            'title' => 'Your order is rejected',
            'content' => 'Order no. ' . $request->order_id . ' is rejected',
            'date' => now()
        ]);

        $tokens = $resturant->tokens()->where('token', '!=', '')->pluck('token')->toArray();
        if (count($tokens)) {
            public_path();
            $title = $notification->title;
            $content = $notification->content;
            $data = [
                'order_id' => $order->id,
                'user_type' => 'resturant',
            ];
            $send = notifyByFirebase($title, $content, $tokens, $data);
        }
        $data = [
            'order' => $order->fresh()->load('foods'),
            'notifications' => $send
        ];

        return JsonResponse(1, 'order has been accepted', $data);
    }

    public function myOffers(Request $request)
    {
        $offers = $request->user()->offers()->with('resturant')->latest()->paginate(20);
        return JsonResponse(1, 'successfully', $offers);
    }

    public function newOffer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'cotent' => 'required',
            'price_with_offer' => 'required|numeric',
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d',
            'food_id' => 'required|exists:foods,id'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return JsonResponse(0, $validation->errors()->first(), $data);
        }
        $offer = $request->user()->offers()->create($request->all() + ['resturant_id' => $request->resturant_id, 'food_id' => $request->food_id]);
        return JsonResponse(1, 'adding successfully', $offer);
    }

    public function updateOffer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'cotent' => 'required',
            'price_with_offer' => 'required|numeric',
            'from' => 'required|date_format:Y-m-d',
            'to' => 'required|date_format:Y-m-d',
            'food_id' => 'required|exists:foods,id'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return JsonResponse(0, $validation->errors()->first(), $data);
        }

        $offer = $request->user()->offers()->find($request->offer_id);
        if (!$offer) {
            return JsonResponse(0, 'invalid data');
        }
        $offer->update($request->all());

        return JsonResponse(1, 'update successfully', $offer);
    }

    public function deleteOffer(Request $request)
    {
        $offer = $request->user()->offers()->find($request->offer_id);
        if (!$offer) {
            return JsonResponse(0, 'invalid data');
        }
        $offer->delete();
        return JsonResponse(1, 'deleted successfully');
    }

    public function listNotifications(Request $request)
    {
        $notifications = $request->user('resturant')->notifications()->with('notifiable.neighborhood')->latest()->paginate(20);
        return JsonResponse(1, 'done loading', $notifications);
    }

    public function changeState(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'status' => 'required|in:available,close'
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return JsonResponse(0, $validation->errors()->first(), $data);
        }

        $request->user()->update(['status' => $request->status]);

        return JsonResponse(1, '', $request->user());
    }

    public function commissions(Request $request)
    {
        $count = $request->user()->orders()->where('status', 'accepted')->where(function ($q) {
            $q->where('status', 'delivered');
        })->count();

        $total = $request->user()->orders()->where('status', 'accepted')->where(function ($q) {
            $q->where('status', 'delivered');
        })->sum('total_price');

        $commissions = $request->user()->orders()->where('status', 'accepted')->where(function ($q) {
            $q->where('status', 'delivered');
        })->sum('comision');

        $payments = $request->user()->transactions()->sum('amount');

        $net_commissions = $commissions - $payments;

        $commission = settings()->comission;

        return JsonResponse(1, '', compact('count', 'total', 'commissions', 'payments', 'net_commissions', 'commission'));
    }
}
