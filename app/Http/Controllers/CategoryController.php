<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Categories;

class CategoryController extends Controller
{
    public function create_category(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['vendor_id'] = config('request.vendor_id');
        Categories::insert($data);
        return response()->json([
            'message' => 'Category has been created successfully'
        ]);
    }
    public function get_categories(CategoryRequest $request)
    {
        $categories = Categories::activeCategories()->get();
        if ($categories == null)
            return response()->json([
                'message' => 'No Categories Available'
            ],500);
        return response()->json([
            'data' => $categories
        ],200);
    }
    public function delete_category(CategoryRequest $request)
    {
        $category_id = $request->route('category_id');
        Categories::where('id',$category_id)->delete();
        return response()->json([
            'message' => 'Category has been deleted successfully'
        ]);
    }
    public function edit_category(CategoryRequest $request)
    {
        $data = $request->validated();
        $category_id = $data['category_id'];
        unset($data['category_id']);
        Categories::where('id',$category_id)->update($data);
        return response()->json([
            'message' => 'Category has been edited successfully'
        ]);
    }
}
