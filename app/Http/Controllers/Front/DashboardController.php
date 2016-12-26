<?php

namespace App\Http\Controllers\Front;

use Leadgen\Customer\Customer;
use Leadgen\Interaction\Interaction;

class DashboardController
{
    public function home()
    {
        $viewVars = [
            'interactionCount' => Interaction::all()->count(),
            'customerCount' => Customer::all()->count(),
        ];

        return view('app.home', $viewVars);
    }
}
