<?php

namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'pet'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id'        => (int) $post->hash_id,
            'type'      => $post->type,
            'location'  => [
                'address'   => $post->address,
                'lat'       => $post->lat,
                'lng'       => $post->lng,
            ],
            'created_at'    => (string) $post->created_at,
            'updated_at'    => (string) $post->updated_at,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => route('api.posts.show', ['id' => $post->hash_id]),
                ]
            ],
        ];
    }

    /**
     * Include Pet
     *
     * @return League\Fractal\ItemResource
     */
    public function includePet(Post $post)
    {
        $pet = $post->pet;

        return $this->item($pet, new PetTransformer, 'pet');
    }

}
