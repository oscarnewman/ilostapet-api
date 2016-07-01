<?php

namespace App\Transformers;

use App\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Image $image)
    {
        return [
            'id'            => (int) $image->hash_id,
            'pet_id'       => $image->post_id,
            'image_url'     => $image->image_url,
            'storage_path'  => $image->storage_path,
            'width'         => (int) $image->width,
            'height'        => (int) $image->height,
            'created_at'    => (string) $image->created_at,
            'updated_at'    => (string) $image->updated_at,
        ];
    }

}
