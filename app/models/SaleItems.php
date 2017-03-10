<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    public function sale()
    {
    	return $this->belongsTo(Sale::class);
    }

    public function item()
    {
    	return $this->belongsTo(Item::class);
    }
}
