<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Item;
use Validator;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the inventory request.
        $validator = Validator::make($request->all(), [
            'adjustment' => 'required|not_in:0|numeric',
            'comments' => 'required|min:5|max:50'
        ])->validate();

        // Save the Inventory details
        $inventory = new Inventory();
        $inventory->item_id = $request->id;
        $inventory->user_id = Auth::user()->id;
        $inventory->adjustment = $request->adjustment;
        $inventory->comments = $request->comments;
        $inventory->save();

        // Update The Item Quantity Based on The adjustment
        $item = Item::findOrFail($request->id);
        $oldQuantity = $item->quantity;
        $newQuantity = $oldQuantity + $request->adjustment;
        $item->quantity = $newQuantity;
        $item->update();
    }

    /**
     * Gather all the Inventory adjustment history for a particular
     * Inventory Item.
     */
    public function show($id)
    {
        $item_id = (int)$id;

        return Inventory::with('user')->where('item_id', $item_id)->orderBy('created_at', 'DESC')->limit(5)->get();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
