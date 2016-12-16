<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
	use SoftDeletes;

    public function inventory()
    {
    	return $this->hasMany('App\Inventory');
    }

    public function costs()
    {
    	return $this->hasMany('App\Cost');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
