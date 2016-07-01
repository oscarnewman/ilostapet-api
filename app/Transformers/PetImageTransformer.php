<?php

namespace App\Transformers;

use App\Image;
use League\Fractal\TransformerAbstract;

class PetImageTransformer extends TransformerAbstract
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
            'image_url'     => $image->image_url,
            'width'         => (int) $image->width,
            'height'        => (int) $image->height,
        ];
    }

}
