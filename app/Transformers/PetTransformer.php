<?php

namespace App\Transformers;

use App\Pet;
use League\Fractal\TransformerAbstract;

class PetTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'images',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Pet $pet)
    {
        return [
            'id'        => (int) $pet->hash_id,
            'animal'    => $pet->animal,
            'gender'    => $pet->gender,
            'name'      => $pet->name,
            'breed'     => $pet->breed,
            'description'   => $pet->description,
            'has_collar'    => (bool) $pet->has_collar,
            'has_tags'      => (bool) $pet->has_tags,
            'has_microchip' => (bool) $pet->has_microchip,
            'created_at'    => (string) $pet->created_at,
            'updated_at'    => (string) $pet->updated_at,
        ];
    }

    /**
     * Include Images
     * @return Leage\Fractal\ItemResource
     */
    public function includeImages(Pet $pet) {
        $images = $pet->images;

        return $this->collection($images, new ImageTransformer());
    }

}
