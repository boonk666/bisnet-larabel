<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint as Keluhan;

class KeluhanController extends Controller
{
    
    public function submit(Request $request) {
        Keluhan::create([
            'id_pelanggan' => $request->user()->customer->id_pelanggan,
            'keluhan' => $request->keluhan
        ]);

        return response()->json(['status' => 'success']);
    }

}
