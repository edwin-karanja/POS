<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Item;
use App\Sale;
use Validator;
use App\SaleItems;
use App\Customer;
use App\Inventory;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('menu');

        app('laravel_dashboard')->setPageTitle('POS | Sales');
    }
    /**
     * Get the recent sale items for a particular user.
     */
    public function index(Request $request)
    {
        $userId = Auth::user()->id;

        if ($request->ajax()) {
            return Sale::with('customer')->where('user_id', $userId)->latest()->limit(10)->get();
        }

        return view('sales');
   
    }

    /**
     * Record a new Sale transaction.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|numeric',
            'items.*.qtty' => 'required|numeric',
            'items.*.sprice' => 'required|numeric'
        ])->validate();

        // Save The Sale in The System.
        DB::transaction(function() use ($request) {
            // Persist th Sale to the database
            $sale = new Sale();
            $sale->customer_id = $request->customer_id;
            $sale->payment_type = $request->payment_type;
            $sale->user_id = Auth::user()->id;
            $sale->total = $request->total;

            if ($request->payment_type === 'Credit') {
                $sale->amount_credited = number_format($request->amount_credited, 2);
                $sale->balance = $request->balance;
            } else {
                $sale->amount_credited = $request->total;
                $sale->balance = 0;
            }
            
            $sale->save();

            /**
             * Save the Items pertaining to the sale
             * and adjust the quantity of the Item.
             * Store a record of the adjustment in the 
             * inventory adjustment table.
             */
            foreach($request->items as $row) {
                $item = new SaleItems();
                $item->sale_id = $sale->id;
                $item->item_id = $row['id'];
                $item->cost_price = $row['cost_price'];
                $item->selling_price = $row['sprice'];
                $item->quantity = $row['qtty'];
                $item->item_total = $row['total'];
                $item->save();

                // Decrease the Item Quantity in the Items table
                $stock = Item::findOrFail($row['id']);
                $oldQuantity = $stock->quantity;
                $newQuantity = $oldQuantity - $row['qtty'];
                $stock->quantity = $newQuantity;
                $stock->update();

                // Capture the Inventory transaction
                $inventory = new Inventory();
                $inventory->item_id = $row['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->adjustment = '-' . $row['qtty'];
                $custName = Customer::findOrFail($request->customer_id)->name;
                $inventory->comments = 'RECEIPT no: <strong>' . $sale->id . '</strong> To: <strong>' . $custName . '</strong>.';
                $inventory->save();
            }
        });
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sale::with('user', 'customer', 'items.item')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Get a specific sales record as 
     * well as the sales_items, owner
     * and customer details.
     */
    public function update(Request $request, $id)
    {
        
    }


    public function search(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'search' => 'sometimes'
        // ])->validate();

        $search = $request->search;

        $items = Item::with('category')->where('item_name', 'like', '%' . $search . '%')->get();
        
        return (count($items) > 0) ? $items : Item::all();
    }

    public function suspend(Request $request)
    {
        foreach($request->all() as $sale) {
            dump($sale['id']);
        }


        die();
    }
}
