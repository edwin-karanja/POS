<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(Category $category)
    {
        parent::__construct();
        $this->middleware(['auth', 'menu']);
        app('laravel_dashboard')->setPageTitle('POS | Categories');
        $this->category = $category;
    }

    public function categories()
    {
        return view('categories');
    }

    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|unique:categories',
            'description' => 'sometimes|min:10'
        ])->validate();

        try {
            $this->category->createCategory($request);
            return response()->json(['msg' => "Category {$this->category->category->name} created!"]);
        } catch(\Exception $e) {
            // Log an exception  and return an error message to the user;
            return response()->json(['error' =>true, 'msg' => 'Unable to create new Category, try later.']);
        }
    }

    /**
     * Update the category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = $this->category->find($id);
        $oldName = $category->name;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('categories')->ignore($category->id)
            ]
        ])->validate();

        try {
            $this->category->updateCategory($request);
            return response()->json(['msg' => "Updated category {$oldName} to {$category->name}"]);
        } catch (\Exception $e) {
            // Log an exception  and return an error message to the user;
            return response()->json(['error' =>true, 'msg' => "Unable to update category {$category->name}, try later."]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->category->deleteCategory($id);
            return response()->json(['msg' => "Category {$this->category->category->name} deleted!"]);
        } catch (\Exception $e) {
            // Log exception
            return response()->json(['msg' => "Unable to delete Category {$this->category->category->name}."]);
        }
    }
}
