<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests;
use Illuminate\Http\Request;

class DashboardController
{
    public function home()
    {
        return view('app.home');
    }
}
