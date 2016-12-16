<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspendedSaleItems extends Model
{
    protected $table = 'suspended_sale_items';

    public function suspended()
    {
    	return $this->belongsTo('App\SuspendedSales');
    }
}
