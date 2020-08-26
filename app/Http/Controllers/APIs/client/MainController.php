<?php

namespace App\Http\Controllers\APIs\client;

use App\Models\Food;
use App\Models\Order;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function newOrder(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|exists:resturants,id',
            'foods' => 'required|array',
            'foods.*.food_id' => 'required|exists:foods,id',
            'foods.*.quantity' => 'required',
            'foods.*.note' => 'required',
            'payment_method' => 'required|in:visa,cash',
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }
        $resturant = Resturant::find($request->resturant_id);
        if ($resturant->status == 'close') {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }
        $order = $request->user()->orders()->create([
            'resturant_id' => $request->resturant_id,
            'note' => $request->note,
            'state' => 'pending',
            'delivery_address' => $request->address,
            'payment' => $request->payment_method,
            'client_id' => $request->user('client')->id,
        ]);

        $cost = 0 ;
        $delivery_cost = $resturant->delivery_fee;
        foreach ($request->foods as $i) {
            $food = Food::find($i['food_id']);
            $readyFood = [
                $i['food_id'] => [
                    'quantity' => $i['quantity'],
                    'price' => $food->price,
                    'note' => (isset($i['note'])) ? $i['note'] : ''
                ]
            ];
            $order->foods()->attach($readyFood);
            $cost += ($food->price * $i['quantity']);
        }

        if ($cost >= $resturant->minmum_order) {
            $total = $cost + $delivery_cost;
            $comision = settings()->comission * $cost;

            $net = $total - $comision;
            $order->update([
                'price' => $cost,
                'delivery_fees' => $delivery_cost,
                'total_price' => $total,
                'comision' => $comision,
                'net' => $net
            ]);

            $notification = $resturant->notifications()->create([
                'title' => 'order created',
                'content' => $request->user('client')->name . 'from client ',
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
                'notifications' => $send,
            ];

            return JsonResponse(1, 'successfully', $data);
        } else {
            $order->foods()->delete();
            $order->delete();
            return JsonResponse(0, 'successfully ' . $resturant->minmum_order . ' $');
        }
    }

    public function myOrders(Request $request)
    {
        $order = $request->user()->orders()->where('status', $request->status)->paginate(10);
        return JsonResponse(1, 'successfully', $order);
    }

    public function showOrder(Request $request)
    {
        $order = Order::with('foods', 'resturant.neighborhood', 'resturant.categories')->find($request->order_id);
        return JsonResponse(1, 'successflly', $order);
    }

    public function confirmOrder(Request $request)
    {
        $order = $request->user()->orders()->find($request->order_id);

        if (!$order) {
            return JsonResponse(0, 'not data found');
        }
        if ($order->status != 'accepted') {
            return JsonResponse(0, 'order not accepted');
        }
        $order->update(['status' => 'delivered']);
        $resturant = $order->resturant;
        $notification = $resturant->notifications()->create([
            'title' => 'Your order is delivered to client',
            'content' => 'Order no. ' . $request->order_id . ' is delivered to client',
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

        return JsonResponse(1, 'Order had been deliverd', $data);
    }

    public function declineOrder(Request $request)
    {
        $order = $request->user()->orders()->find($request->order_id);
        if (!$order) {
            return JsonResponse(0, 'not data found');
        }
        if ($order->status != 'accepted') {
            return JsonResponse(0, 'order not accepted');
        }
        $order->update(['status' => 'declined']);
        $resturant = $order->resturant;
        $notification = $resturant->notifications()->create([
            'title' => 'Your order delivery is declined by client',
            'content' => 'Delivery if order no. ' . $request->order_id . ' is declined by client',
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

        return JsonResponse(1, 'Order had been deliverd', $data);
    }



    public function createComment(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'emoji' => 'required|in:veryhappy,happy,likely,sad,verysad',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return JsonResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $records = Comment::create($request->all() + ['client_id' => $request->user('client')->id]);

        return JsonResponse(1, 'successfully', $records);
    }
    public function listNotifications(Request $request)
    {
        $notifications = $request->user('client')->notifications()->with('notifiable.neighborhood')->latest()->paginate(20);
        return JsonResponse(1, 'done loading', $notifications);
    }
}
