<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
	use SoftDeletes;
	
    protected $table = 'item_costs';

    public function Item()
    {
    	return $this->belongsTo('App\Item');
    }

    
}
