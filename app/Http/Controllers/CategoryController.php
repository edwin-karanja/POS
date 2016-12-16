<?php

namespace App\Http\Controllers;

use App\Category;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('menu');

        app('laravel_dashboard')->setPageTitle('POS | Categories');
    }

    public function categories()
    {
        return view('categories');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|unique:categories',
        ])->validate();

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('categories')->ignore($category->id)
            ]
        ])->validate();

        $category->name = $request->name;
        $category->description = $request->description;
        $category->update();
    }

    /**
     * Delete the category and update the
     * category_id field in the items table.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->items()->update(['category_id' => null]);

        $category->delete();
    }
}
