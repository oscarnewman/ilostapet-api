<?php

namespace App\Transformers;

use App\Pet;
use League\Fractal\TransformerAbstract;

class PetTransformer extends TransformerAbstract
{
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
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => route('api.pets.show', ['id' => $pet->hash_id]),
                ]
            ],
        ];
    }

}
