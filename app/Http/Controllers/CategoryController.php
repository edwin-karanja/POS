<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->middleware(['auth', 'menu']);
        app('laravel_dashboard')->setPageTitle('POS | Categories');
        $this->category = $category;
    }

    public function categories()
    {
        $cats = $this->category->with('itemCount')->get();
        return view('categories')->with(['cats' => $cats]);
    }

    public function index()
    {
        return $this->category->get(['*']);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|min:5|unique:categories',
            'description' => 'sometimes|min:10'
        ])->validate();

        try {
            $category = $this->category->createCategory($request);
            return response()->json(['msg' => "Category {$category->name} created!"]);
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
        $category = $this->category->findById($id);
        Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('categories')->ignore($category->id)
            ]
        ])->validate();

        $this->category->updateCategory($request, $id);
        return response()->json(['success' => true, 'msg' => "Updated category {$category->name} to {$this->category->getColumn('name', $id)}"]);
    }

    public function destroy($id)
    {
        $category = $this->category->findById($id);
        if ($category) {

        $this->category->deleteCategory($id);
        return response()->json(['success' => true, 'msg' => "Category {$category->name} deleted!"]);

        }
        
    }
}
