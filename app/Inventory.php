<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $fillable = ['adjustment', 'comments'];

    public function Item()
    {
    	return $this->hasOne('App\Item');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

}
