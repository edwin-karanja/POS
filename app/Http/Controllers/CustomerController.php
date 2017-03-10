<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');

        $this->middleware('menu');

        app('laravel_dashboard')->setPageTitle('POS | Customer');
    }

    public function getCustomers(Request $request)
    {
    	if ($request->ajax()) {
    		return Customer::all();
    	}

    	return view('customer');
    }

    public function postCustomers(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'name' => 'required|unique:customers',
    		'phone_number' => 'sometimes|numeric|digits_between:9,11',
    		'email' => 'sometimes|email',
    		'address' => 'sometimes'
    	])->validate();

    	$customer = new Customer();
    	$customer->name = $request->name;
    	$customer->email = $request->email;
    	$customer->phone_number = $request->phone_number;
    	$customer->address = $request->address;
    	$customer->save();
    }

    public function updateCustomer(Request $request, $id)
    {
    	$customer = Customer::findOrFail($id);



    	$validator = Validator::make($request->all(), [
    		'name' => [
                'required',Rule::unique('customers')->ignore($customer->id),
            ],
    		'phone_number' => 'sometimes|numeric|digits_between:9,11',
    		'email' => 'sometimes|email',
    		'address' => 'sometimes'
    	])->validate();


    	$customer->name = $request->name;
    	$customer->email = $request->email;
    	$customer->phone_number = $request->phone_number;
    	$customer->address = $request->address;
    	$customer->update();
    }

    public function deleteCustomer($id)
    {
    	$customer = Customer::findOrFail($id);

    	$customer->delete();
    }

    public function getHistory(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $sales = Customer::findOrFail($id)->sales()->where('payment_type', 'Cash')->latest()->paginate(20);

        $creditSales = Customer::findOrFail($id)->sales()->where('payment_type', 'Credit')->latest()->paginate(20);

        $allSales = Customer::findOrFail($id)->sales()->get();

        $todaySale = Customer::findOrFail($id)->sales()->whereDate('created_at', date('Y-m-d'))->get();

        $countAllSales = count($allSales);

        $countTodaySales = count($todaySale);

        $cust = new Customer();

        $totalSale = $cust->totalSales($allSales);

        

        $todayTotal = $cust->totalSales($todaySale);

        return view('customer.info', compact('customer', 'sales', 'creditSales', 'totalSale', 'countAllSales', 'countTodaySales', 'todayTotal'));
    }

    public function getSales($id)
    {
        $customer = Customer::findOrFail($id);
    }
}
