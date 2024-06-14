<?php

namespace App\Http\Controllers;

use App\Enums\ExceptionEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\ProductRequest;
use App\Http\Services\ImageService;
use App\Models\Categories;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create_product(ProductRequest $request)
    {
        $data = $request->validated();
        $images  = $request->allFiles();
        DB::beginTransaction();
        $image_service = new ImageService();
        foreach($images as $image) {
            $image_service->save_image_vendor($image,"product_image");
        }
        DB::commit();
        return response()->json([
            'messsage' => 'Product saved successfully'
        ]);

    }
    public function get_product_by_id(ProductRequest $request)
    {
        $product_id = $request->validated('product_id');
        $data = Products::where('id', $product_id)->with([
            'product_details',
            'added_by',
            'category'
        ])
        ->first();
        if($data == null) {
            throw new CustomException(ExceptionEnum::SomethingWentWrong);
        }
        return response()->json([
            'data' => $data
        ], 200);
    }
    public function get_products(ProductRequest $request)
    {
        $category = $request->query('category') ?? null;
        $data = Products::with(['product_details','added_by']);
        if($category != null){
            $categories = Categories::where('name',$category)->select('id')->first();
            if($categories == null) {
                return response()->json([
                    'message' => 'Category doesnot exist'
                ]);
            }
            $categories = array_keys($categories->toArray());
            $data = $data->whereIn('category_id',$categories);
        }
        $data = $data->get()->toArray();
        if($data == null or empty($data)) {
            return response()->json([
                    'message' => 'No products avaiable'
                ]);
        }
        return response()->json([
            'data' => $data
        ], 200);

    }
    public function update_product(ProductRequest $request)
    {
        $data = $request->validated();
        // if($data[''])
    }
    public function delete_product(ProductRequest $request)
    {
        $data = Products::where('id', $request->product_id)->delete();
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
