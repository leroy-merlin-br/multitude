<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests;
use Illuminate\Http\Request;

class SegmentController
{
    public function home(Request $request)
    {
        $viewVars = [
            'search' => $request->input('search', '')
        ];

        return view('app.segment', $viewVars);
    }
}
