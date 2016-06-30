<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HashIDModel;

class Pet extends Model
{
    use HashIDModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'animal', 'gender', 'name', 'breed', 'description', 'has_collar', 'has_tags', 'has_microchip', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->hasOne(Post::class);
    }
}
