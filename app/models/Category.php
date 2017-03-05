<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    public $category;

    protected $fillable = ['name', 'description'];

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function createCategory(Request $r)
    {
        $this->category = self::create([
            'name' => $r->name,
            'description' => $r->description
        ]);
    }

    public function updateCategory(Request $r)
    {
        $this->category->update([
            'name' => $r->name,
            'description' => $r->description
        ]);
    }

    /**
     * Delete the category and update the
     * category_id field in the items table.
     */
    public function deleteCategory($id)
    {
        $this->find($id);
        $this->category->items()->update(['category_id' => null]);
        $this->category->delete();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    public function find($id)
    {
        $category = self::findOrFail($id);
        $this->category = $category ? $category : new self();
        return $this->category;
    }
}
