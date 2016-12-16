<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    	$routes = ['/', 'home', 'items', 'sales', 'customers', 'suppliers'];

    	View::share('routes', $routes);
    }
}