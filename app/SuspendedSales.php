<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspendedSales extends Model
{
    protected $table = 'suspended_sales';

    public function items()
    {
    	return $this->hasMany('App\SuspendedSaleItems');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }
}
