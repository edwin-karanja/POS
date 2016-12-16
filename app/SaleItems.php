<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    public function sale()
    {
    	return $this->belongsTo('App\Sale');
    }

    public function item()
    {
    	return $this->belongsTo('App\Item');
    }
}
