<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspendedSaleItems extends Model
{
    protected $table = 'suspended_sale_items';

    public function suspended()
    {
    	return $this->belongsTo(SuspendedSales::class);
    }
}
