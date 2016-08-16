<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HashIDModel;

class Contact extends Model
{
    use HashIDModel;

    protected $table = "contact_info";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'post_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'id',
    ];

}
