<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $fillable = ['adjustment', 'comments'];

    public function Item()
    {
    	return $this->hasOne(Item::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
