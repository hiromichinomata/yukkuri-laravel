<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        return view('chat.index');
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $payload = [
            'user' => $request->user()->name,
            'message' => $validated['message'],
        ];

        broadcast(new MessageSent($payload))->toOthers();

        return response()->json(['status' => 'Message Sent!', 'data' => $payload]);
    }
}
