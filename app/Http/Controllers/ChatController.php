<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ─── Customer / Member Methods ─────────────────────────
    public function indexCustomer()
    {
        $chat = Chat::firstOrCreate(['customer_id' => auth()->id()]);
        
        // Mark read
        $chat->update(['unread_by_customer' => false]);
        
        $messages = $chat->messages;
        
        return view('dashboard.customer.chat', compact('chat', 'messages'));
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::firstOrCreate(['customer_id' => auth()->id()]);
        
        $chat->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $request->message,
        ]);

        $chat->update([
            'last_message'  => $request->message,
            'unread_by_cs'  => true,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    // ─── CS Methods ────────────────────────────────────────
    public function indexCS()
    {
        $chats = Chat::with(['customer'])->orderBy('updated_at', 'desc')->get();
        $chat = null;
        $messages = collect();
        
        return view('dashboard.cs.chat', compact('chats', 'chat', 'messages'));
    }

    public function showCS(Chat $chat)
    {
        $chats = Chat::with(['customer'])->orderBy('updated_at', 'desc')->get();
        
        // Mark read
        $chat->update(['unread_by_cs' => false]);
        
        $messages = $chat->messages;
        
        return view('dashboard.cs.chat', compact('chats', 'chat', 'messages'));
    }

    public function storeCS(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $request->message,
        ]);

        $chat->update([
            'last_message'       => $request->message,
            'unread_by_customer' => true,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    // ─── Polling API ───────────────────────────────────────
    public function getMessages(Chat $chat)
    {
        if (auth()->user()->role !== 'cs' && auth()->id() != $chat->customer_id) {
            abort(403);
        }

        // Mark as read depending on who is requesting
        if (auth()->user()->role === 'cs') {
            $chat->update(['unread_by_cs' => false]);
        } else {
            $chat->update(['unread_by_customer' => false]);
        }

        $messages = $chat->messages()->with('sender')->get()->map(function($msg) {
            return [
                'id'          => $msg->id,
                'sender_id'   => $msg->sender_id,
                'sender_role' => $msg->sender->role,
                'sender_name' => $msg->sender->name,
                'message'     => $msg->message,
                'time'        => $msg->created_at->format('H:i'),
            ];
        });

        return response()->json(['messages' => $messages]);
    }
}
