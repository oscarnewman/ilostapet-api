<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HashIDModel;

use Intervention;
use Storage;

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

    public function pets() {
        $this->belongsTo(Pet::class);
    }

    public function deleteS3() {
        Storage::disk('s3')->delete($this->storage_path);
    }

    public static function prepareAndCreate($file) {
        $image_file = Intervention::make($file)->orientate();

        // Resize image
        $image_max_size = 1024;
        $callback = function ($constraint) { $constraint->upsize(); };
        $image_file->widen($image_max_size, $callback)->heighten($image_max_size, $callback);

        $payload = (string)$image_file->encode();

        //Use some method to generate your filename here.
        $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();

        $base_url = 'uploads/pets/images';

        $image_path = "uploads/pets/images/$filename";
        //Push file to S3
        Storage::disk('s3')->put($image_path, $payload);


        //input a row into the database to track the image (if needed)
        $image = new Image([
            'pet_id'       => NULL,
            'image_url'     => self::url($image_path),
            'storage_path'  => $image_path,
            'width'         => $image_file->width(),
            'height'        => $image_file->height(),
        ]);

        $image->save();

        return $image;
    }

    static function url($path) {
        return "https://static.ilostapet.net/$path";
    }

}
