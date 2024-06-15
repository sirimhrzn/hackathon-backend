<?php

namespace App\Http\Controllers; use App\Enums\ExceptionEnum;
use App\Exceptions\CustomException;
use App\Http\Requests\ProductRequest;
use App\Http\Services\ImageService;
use App\Models\Categories;
use App\Models\ProductDetails;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create_product(ProductRequest $request)
    {
        $data = $request->all();
        $images  = $request->allFiles();
        DB::beginTransaction();
        $create_data = [];
        $model = new Products();
        $model->name = $data['name'];
        $model->enabled = $data['enabled'];
        $model->category_id = $data['category_id'];
        $model->price = $data['price'];
        $model->vendor_id = config('request.vendor_id');
        $model->added_by = 1;
        $model->save();

        $create_data = [];
        $create_data['metadata']['description'] = $data['description'];
        $sizes = explode(",",$data['size']);
        $counts = explode(",",$data['stock']);
        $stocks = [];
        foreach($sizes as $index=>$value){
            $stocks[] = [
                'size' => $value,
                'stock' => (int)$counts[$index]
            ];
        }
        $create_data['metadata']['types'] = $stocks;
        $image_service = new ImageService();
        foreach($images as $index=>$image) {
            $path = $image_service->save_image_vendor($image,"product_image");
            $create_data['metadata']['images'][] = $path;
        }
        $details = new ProductDetails();
        $details->product_id = $model->id;
        $details->metadata = json_encode($create_data['metadata']);
        $details->vendor_id = config('request.vendor_id');
        $details->details = json_encode([]);
        $details->save();
        // dd($create_data);
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
        $prod_id = $request->product_id;
        DB::transaction(function() use ($prod_id){
            ProductDetails::where('product_id',$prod_id)->delete();
            Products::where('id', $prod_id)->delete();
        });
        // $data = Products::where('id', $request->product_id)->delete();
        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
