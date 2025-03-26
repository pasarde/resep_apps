<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return Chat::with('user')->get();
    }

    public function store(Request $request)
    {
        $chat = new Chat();
        $chat->message = $request->message;
        $chat->user_id = $request->user()->id;
        $chat->save();

        return $chat->load('user');
    }
}