<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;

class Item extends Model
{
	use SoftDeletes, LogsActivity;

    protected $fillable = [
        'item_name', 'upc_ean_isbn', 'packaging', 'description',
        'cost_price', 'selling_price', 'category_id', 'quantity'
    ];

    protected static $logAttributes = ['item_name', 'description', 'category_id', 'cost_price', 'selling_price'];

    // The name of the Log for this model.
    public function getLogNameToUse($eventName = '')
    {
        return 'stocks';
    }

    public function inventory()
    {
    	return $this->hasMany(Inventory::class);
    }

    public function costs()
    {
    	return $this->hasMany(Cost::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saveInventory(Request $r)
    {
        $item = self::create([
            'item_name' => $r->item_name,
            'upc_ean_isbn' => $r->upc_ean_isbn,
            'packaging' => $r->packaging,
            'description' => $r->description,
            'cost_price' => $r->cost_price,
            'selling_price' => $r->selling_price,
            'category_id' => $r->category,
            'quantity' => $r->quantity,
        ]);
        $item->inventory()->save($this->getItemInventory($item));
        return $item;
    }

    private function  getItemInventory($item)
    {
        $inventory = new Inventory();
        $inventory->adjustment = $item->quantity ? $item->quantity : 0;
        $inventory->comments = 'Initial Manual Entry';
        $inventory->user_id = Auth::user()->id;
        return $inventory;
    }

    public function findById($id)
    {
        return self::find($id);
    }

    public function deleteStockAndPrice($id)
    {
        $item = $this->findById($id);
        $item->delete();
        $item->costs()->delete();
    }

}
