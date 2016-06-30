<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Optimus\Optimus;
use App\Traits\HashIDModel;

class Post extends Model
{
    use HashIDModel;

    /**
     * Fields to be appended to the Eloquent modle
     * @var [type]
     */
    protected $appends = [
        // 'hash_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'address', 'lat', 'lng', 'pet_id'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function contactInfo() {
        return $this->hasMany(ContactInfo::class);
    }

}
