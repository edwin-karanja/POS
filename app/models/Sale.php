<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SaleItems;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function items()
    {
    	return $this->hasMany(SaleItems::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function customer()
    {
    	return $this->belongsTo(Customer::class);
    }
}
