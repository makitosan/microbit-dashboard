<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\MessageRecieved;
use Illuminate\Http\Request;
use App\Models\MicrobitData;

class DataController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required|numeric'
        ]);

        $messages = [[
            'time' => $request->time,
            'ave_a_x' => $request->ave_a_x,
            'ave_a_y' => $request->ave_a_y,
            'ave_a_z' => $request->ave_a_z
            ]];

        event(new MessageRecieved($messages));

        // save to db
        $data = new MicrobitData();
        $data->time = $request->time;
        $data->data = $request->all();
        $data->save();

        return response()->json($messages);
    }

    /**
     * Retrieve the latest data
     */
    public function latest() {
        // get data from redis, if it returns empty, then get data from DB
        $microbit_data = MicrobitData::orderBy('time', 'desc')->first();
        return response()->json($microbit_data->data);
    }

    /**
     * Retrieve the latest 360 data
     */
    public function recent() {
        // get data from redis, if it returns empty, then get data from DB
        $microbit_datas = MicrobitData::orderBy('time', 'desc')->limit(360)->get();
        $datas = [];
        foreach($microbit_datas as $data) {
            $datas[] = $data->data;
        }
        return response()->json($datas);
    }

}
