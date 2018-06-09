<?php

namespace App\Http\Controllers;

use App\Events\MessageRecieved;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:255'
        ]);

        $messages = [['text' => $request->text]];

        event(new MessageRecieved($messages));

        return response()->json($messages);
    }
}
