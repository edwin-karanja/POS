<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Item;
use App\Models\Cost;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    private $item;

    public function __construct(Item $item)
    {
        $this->middleware(['auth', 'menu']);
        app('laravel_dashboard')->setPageTitle('POS | Inventory');
        $this->item = $item;
    }

    public function items()
    {
        return view('items');
    }

    public function index()
    {
        return $this->item->with('category')->get();
        // dump($this->item->get(['*']));
    }

    public function store(Request $request)
    {

        Validator::make($request->all(), [
            'item_name' => 'required|unique:items',
            'cost_price' => 'required|numeric|not_in:0',
            'selling_price' => 'required|numeric|not_in:0',
            'quantity' => 'required|numeric|not_in:0',
            'category' => 'required'
        ])->validate();

        try {
            $item = $this->item->saveInventory($request);
            return response()->json(['success' => true, 'msg' => "New Stock [{$item->item_name}] created."]);
        } catch (\Exception $e) {
            // Log message
            return response()->json(['success' => false, 'msg' => "Unable to save new stock. Please try later."]);
        }
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
     * SoftDelete The Item from the database
     * As wellas the prices associated with it.
     */
    public function destroy($id)
    {
        $item = $this->item->findById($id);
        if ($item) {
            $this->item->deleteStockAndPrice($id);
            return response()->json(['success' => true, 'msg' =>"Item [{$item->item_name}] deleted."]);
        }
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
