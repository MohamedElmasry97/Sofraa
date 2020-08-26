<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $order = Order::where(function ($q) use ($request) {
            if ($request->has('order_id')) {
                $q->where('id', $request->order_id);
            }

            if ($request->has('resturant_id')) {
                $q->where('resturant_id', $request->resturant_id);
            }

            if ($request->has('status')) {
                $q->where('status', $request->status);
            }

            if ($request->has('from') && $request->has('to')) {
                $q->whereDate('created_at', '>=', $request->from);
                $q->whereDate('created_at', '<=', $request->to);
            }
        })->with('resturant')->latest()->paginate(20);

        return view('admin.orders.index', compact('order'));
    }

    public function show($id)
    {
        $order = Order::with('resturant', 'foods', 'client')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function print_invoice($id)
    {
        $order = Order::with('address', 'resturant', 'foods', 'comments', 'user')->findOrFail($id);
        return view('layouts.print', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $update = $order->update($request->all());

        $user = $order->user;

        if ($update) {
            if ($request->status == 'accepted') {
                $notificationData = [
                    'title' => 'تم تأكيد طلبك رقم ' . $order->id,
                    'title_en' => 'Your Order #' . $order->id . ' has been  accepted !',
                ];
                $user->notifications()->create($notificationData);

                $audience = ['1include_player_ids' => $user->tokens()->pluck('token')->toArray()];
                $contents = [
                    'en' => 'Your Order #' . $order->id . ' has been  accepted !',
                    'ar' => 'تم تأكيد طلبك رقم ' . $order->id,
                ];
                $data = ['order_id' => $order->id];
                notifyByOneSignal($audience, $contents, $data);
            } elseif ($request->status == 'rejected') {
                $notificationData = [
                    'title' => 'تم رفض طلبك رقم ' . $order->id,
                    'title_en' => 'Your Order #' . $order->id . ' has been  rejected !',
                    'order_id' => $order->id
                ];
                $user->notifications()->create($notificationData);

                $audience = ['include_player_ids' => $user->tokens()->pluck('token')->toArray()];
                $contents = [
                    'en' => 'Your Order #' . $order->id . ' has been  rejected !',
                    'ar' => 'تم رفض طلبك رقم ' . $order->id,
                ];
                $data = ['order_id' => $order->id];
                notifyByOneSignal($audience, $contents, $data);
            } elseif ($request->status == 'canceled') {
                $notificationData = [
                    'title' => 'تم إلغاء طلبك رقم ' . $order->id,
                    'title_en' => 'Your Order #' . $order->id . ' has been canceled !',
                    'order_id' => $order->id
                ];
                $user->notifications()->create($notificationData);

                $audience = ['include_player_ids' => $user->tokens()->pluck('token')->toArray()];
                $contents = [
                    'en' => 'Your Order #' . $order->id . ' has been canceled !',
                    'ar' => 'تم إلغاء طلبك رقم ' . $order->id,
                ];
                $data = ['order_id' => $order->id];
                notifyByOneSignal($audience, $contents, $data);
            }
            flash()->success('تم تعديل الحالة  بنجاح');
            return redirect('admin/order/' . $id);
        }
        return redirect('admin/order/' . $id);
    }
}
