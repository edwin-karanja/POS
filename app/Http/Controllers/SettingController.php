<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Validator;
use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth', 'menu']);
        app('laravel_dashboard')->setPageTitle('POS | Settings');
    }

    public function index()
    {
    	return view('settings.home');
    }

    public function uploadcsv(Request $request)
    {
    	Validator::make($request->all(), [
    		'csv_file' => 'not_in:undefined|mimes:csv,xlsx,xls|max:4000'
    	])->validate();

    	if ($request->file('csv_file')->isValid()) {
    		$file = $request->file('csv_file');

    		$path = $file->store('public');
    		$arr = explode('/', $path);
    		$path = $arr[1];

    		$result = Excel::load('storage/'.$path, function($render) {

    		})->get();

    		return $result;


    		// $results = Excel::filter('chunk')->load('storage/'.$path)->chunk(250, function($rows) {

    		// });

    		// return $results();

    	}
    }

    public function persist(Request $request)
    {
    	if ($request->file('csv_file')->isValid()) {
    		$file = $request->file('csv_file');

    		$path = $file->store('public');
    		$arr = explode('/', $path);
    		$path = $arr[1];

    		$results = Excel::load('storage/'.$path, function($render) {

    		})->get();



			Validator::make($results->toArray(), [
				'*.upc_ean_isbn' => 'sometimes',
				'*.item_name' => 'required|min:3|max:200|distinct',
				'*.cost_price' => 'required|numeric|not_in:0',
				'*.selling_price' => 'required|numeric|not_in:0',
				'*.packaging' => 'sometimes|alpha|min:3|max:10'
			])->validate();

			foreach ($results as $result) {
				// Persist the item to the database.
		        $item = new Item();
		        $item->item_name = $result->item_name;
		        $item->upc_ean_isbn = $result->upc_ean_isbn;
		        $item->packaging = $result->packaging;
		        $item->description = $result->description;
		        $item->cost_price = $result->cost_price;
		        $item->selling_price = $result->selling_price;
		        $item->category_id = $result->category;
		        $item->quantity = $result->quantity ? $result->quantity : 0;

		        $item->save();

		        // Add the quantity to the inventory table
		        $inventory = new Inventory();
		        $inventory->adjustment = $item->quantity ? $item->quantity : 0;
		        $inventory->comments = 'Initial Manual Entry';
                $inventory->user_id = Auth::user()->id;

		        $item->inventory()->save($inventory);
			}


    	}
    }

    public function getProfile($id)
    {
        $user = User::findOrFail($id);

        return view('profile', ['user' => $user]);
    }
}
