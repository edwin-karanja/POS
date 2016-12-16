<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuspendedSalesController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');

        $this->middleware('menu');

        app('laravel_dashboard')->setPageTitle('POS | Sales');
    }

    public function suspendSale(Request $request)
    {
    	dump($request->all());
    }
}
