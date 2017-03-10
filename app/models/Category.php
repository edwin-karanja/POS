<?php

namespace App\Models;


use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Category extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description'];

    // Attributes to be logged when created, updated or deleted.
    protected static $logAttributes = ['name', 'description'];

    // The name of the Log for this model.
    public function getLogNameToUse($eventName = '')
    {
        return 'category';
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function itemCount()
    {
        return $this->hasMany(Item::class)->selectRaw('category_id, count(*) as count')->groupBy('category_id');
    }

    public function createCategory(Request $r)
    {
        return self::create([
            'name' => $r->name,
            'description' => $r->description
        ]);
    }

    public function updateCategory(Request $r, $id)
    {
        $category = $this->findById($id);
        $category->update([
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
        $category = $this->findById($id);
        $category->items()->update(['category_id' => null]);
        $category->delete();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    public function findById($id)
    {
        return self::find($id);
    }

    public function getColumn($column, $id)
    {
        $category = $this->findById($id);
        return $category ? $category->$column : null;
    }
}
