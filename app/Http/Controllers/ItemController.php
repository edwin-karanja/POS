<?php

namespace App\Http\Controllers;

use Auth;
use App\Item;
use App\Cost;
use Validator;
use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('menu');

        app('laravel_dashboard')->setPageTitle('POS | Stocks');
    }

    public function items()
    {
        return view('items');
    }


    /**
     * Get a listing of all the Inventory Items
     * with their catgories.
     */
    public function index()
    {
        return Item::with('category')->get();
    }

    /**
     * Persist a new Inventory Item into the database.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item_name' => 'required|unique:items',
            'cost_price' => 'required|numeric|not_in:0',
            'selling_price' => 'required|numeric|not_in:0',
            'quantity' => 'required|numeric|not_in:0',
        ])->validate();

        // Persist the item to the database.
        $item = new Item();
        $item->item_name = $request->item_name;
        $item->upc_ean_isbn = $request->upc_ean_isbn;
        $item->packaging = $request->packaging;
        $item->description = $request->description;
        $item->cost_price = $request->cost_price;
        $item->selling_price = $request->selling_price;
        $item->category_id = $request->category;
        $item->quantity = $request->quantity;

        $item->save();

        // Add the quantity to the inventory table
        $inventory = new Inventory();
        $inventory->adjustment = $item->quantity ? $item->quantity : 0;
        $inventory->comments = 'Initial Manual Entry';
        $inventory->user_id = Auth::user()->id;

        $item->inventory()->save($inventory);
    }

    /**
     * Get a specific Item and its details.
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            return Item::findOrFail($id);
        } else {
            $item = Item::with(['inventory' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }])->find($id);

            return view('item', ['item' => $item]);
        }

    }


    /**
     * Update the sItem details in the storage
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'item_name' => [
                'required',Rule::unique('items')->ignore($item->id),
            ]
        ])->validate();

        $item->item_name = $request->item_name;
        $item->upc_ean_isbn = $request->upc_ean_isbn;
        $item->packaging = $request->packaging;
        $item->description = $request->description;
        $item->category_id = $request->category;
        $item->update();
    }

    /**
     * SoftDelete The Item from the database.
     */
    public function destroy($id)
    {
        // Delete The Item as well as its price history
        $item = Item::findOrFail($id);

        $item->delete();

        $item->costs()->delete();
    }

    /**
     * Search for a specific Item in the database
     */
    public function search(Request $request)
    {
        $search = $request->search ? $request->search : '';

        $items = Item::with('category')->where('item_name', 'like', '%' . $search . '%')->get();

        $cats = Item::with('category')->whereHas('category', function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->get();

        return (count($items) === 0) ? $cats : $items;
    }
}
