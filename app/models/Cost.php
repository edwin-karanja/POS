<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
	use SoftDeletes;
	
    protected $table = 'item_costs';

    public function Item()
    {
    	return $this->belongsTo(Item::class);
    }

    
}
