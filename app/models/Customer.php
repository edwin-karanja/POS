<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['name', 'email', 'address', 'phone_number'];

    public function sales()
    {
    	return $this->hasMany(Sale::class);
    }

    public function totalSales($sales)
    {
    	$totalSale = 0;

        foreach($sales as $sale) {
            $total = str_replace(',', '', $sale->total);
            $totalSale += (int)$total;
        }

        return $totalSale;
    }
}
