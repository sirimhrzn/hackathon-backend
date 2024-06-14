<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class BaseModel extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('vendor', function (EloquentBuilder $builder) {
            $builder->where('vendor_id', config('request.vendor_id'));
        });
    }
    protected function decode_json_field($value)
    {
        if($value == null) {
            return null;
        }
        return json_decode($value);
    }
    public function with_pagination($parameters, $path)
    {
        $model = $this;
        foreach($parameters['filter'] as $filter => $value) {
            if($filter == 'order_by') {
                $data = explode(",", $value);
                if(!isset($data[1])) {
                    array_push($data, 'desc');
                }
                $model = $model->orderBy($data[0], $data[1]);
            }
            if($filter == 'with') {
                $value = explode(",", $value);
                $model = $model->with($value);
            }
        }
        return $model->paginate($parameters["limit"])->withPath($path)->appends($parameters);
    }
}
