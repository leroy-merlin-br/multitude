<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class DashboardController
{
    public function home()
    {
        return view('dashboard.home');
    }
}
