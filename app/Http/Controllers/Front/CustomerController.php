<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

class CustomerController
{
    public function home(Request $request)
    {
        $viewVars = [
            'search' => $request->input('search', ''),
        ];

        return view('app.customer', $viewVars);
    }
}
