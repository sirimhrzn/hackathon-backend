<?php

namespace App\Http\Controllers;

use App\Enums\ExceptionEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\ProductRequest;
use App\Http\Services\ImageService;
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
        return response()->json($data, 200);
    }
    public function get_products(ProductRequest $request)
    {
        $parameters = $request->query();
        $parameters['limit'] = $parameters['limit'] ?? 10;
        $path = $request->url();
        $model = new Products();
        $model = $model->with_pagination($parameters, $path);
        $data = $model;
        if($data == null) {
            throw new CustomException(ExceptionEnum::SomethingWentWrong);
        }
        return response()->json($data, 200);

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
