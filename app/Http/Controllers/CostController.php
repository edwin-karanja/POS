<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Item;
use Validator;
use Illuminate\Http\Request;

class CostController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'cost' => 'required|numeric|not_in:0',
            'selling_price' => 'required|numeric|not_in:0'
        ])->validate();

        //Update the Item cost_price and Selling Price in The Items table.
        $item = Item::findOrFail($request->item_id);
        $oldCost = $item->cost_price;
        $oldSelling = $item->selling_price;

        if ($oldCost != $request->cost || $oldSelling != $request->selling_price) {
            // Insert New Prices Into The Costs Table
            $cost = new Cost();
            $cost->item_id = $request->item_id;
            $cost->cost = $request->cost;
            $cost->selling_price = $request->selling_price;
            $cost->save();
        }

        $item->cost_price = $request->cost;
        $item->selling_price = $request->selling_price;
        $item->update();
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
