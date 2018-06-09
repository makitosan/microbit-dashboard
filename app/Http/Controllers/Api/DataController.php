<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $messages = [[
            'time' => $request->time,
            'text' => $request->text,
            'ave_a_x' => $request->ave_a_x,
            'ave_a_y' => $request->ave_a_y,
            'ave_a_z' => $request->ave_a_z
            ]];

        event(new MessageRecieved($messages));

        return response()->json($messages);
    }
}
