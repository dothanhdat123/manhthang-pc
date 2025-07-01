<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Hiển thị trang chat
     */
    public function index()
    {
        $customer = auth('web')->user(); // Lấy thông tin từ guard 'web'
        dd($customer);

        $messages = Message::query()
            ->with('store')
            ->when($customer->role != 1, function ($query) use ($customer) {
                $query->where('store_id', $customer->store_id);
            })
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return view('client.chat.index', compact('messages'));
    }

    /**
     * Gửi tin nhắn
     */
    public function send(Request $request)
    {
        $customer = auth('web')->user();

        $request->validate([
            'name' => 'required|string|max:191',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'name' => $request->name,
            'message' => $request->message,
            'store_id' => $customer->store_id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Lấy danh sách tin nhắn mới nhất
     */
    public function fetch()
    {
        $customer = auth('web')->user();

        $messages = Message::query()
            ->with('store')
            ->when($customer->role != 1, function ($query) use ($customer) {
                $query->where('store_id', $customer->store_id);
            })
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return response()->json($messages);
    }
}
