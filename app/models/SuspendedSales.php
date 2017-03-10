<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SuspendedSales extends Model
{
    protected $table = 'suspended_sales';

    public function items()
    {
    	return $this->hasMany(SuspendedSaleItems::class);
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
