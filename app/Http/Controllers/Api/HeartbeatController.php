<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \DateTime;

class HeartbeatController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function beat(Request $request)
    {
        $messages = ['status' => 'ok', 'time' => new DateTime()];
        return response()->json($messages);
    }
}
