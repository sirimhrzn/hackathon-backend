<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class ImageService {
    public function save_image_vendor($image,$type)
    {
        $image_path = config('request.vendor_id') . "/$type/" . $image->getClientOriginalName();
        $response = Storage::disk('minio')->put($image_path,file_get_contents($image),'public');
        return null;
    }
}
