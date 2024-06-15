<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function get_image(Request $request) {
        $vendor_id = config('request.vendor_id');
        $image_name = $request->route('image_name');
        $path = "$vendor_id/product-image/$image_name";
        $image = Storage::disk('minio')->get($path);
	    if (!$image) throw new Exception("Error retrieving image");
        return response($image)->header(
            'Content-Type',
            'image/jpeg'
        );
    }
}
