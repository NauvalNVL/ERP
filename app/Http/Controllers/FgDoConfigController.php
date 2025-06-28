<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FgDoConfig; // Assuming you will create this model

class FgDoConfigController extends Controller
{
    /**
     * Get the FG/DO configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getConfig(Request $request)
    {
        $config = FgDoConfig::firstOrCreate(['id' => 1]); // Assuming a single configuration record
        return response()->json($config);
    }

    /**
     * Update the FG/DO configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateConfig(Request $request)
    {
        $config = FgDoConfig::firstOrCreate(['id' => 1]);
        $config->update($request->all());
        return response()->json($config);
    }
} 