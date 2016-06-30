<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HashIDModel;

class Image extends Model
{
    use HashIDModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'image_url', 'storage_path', 'width', 'height'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'id'
    ];

}
