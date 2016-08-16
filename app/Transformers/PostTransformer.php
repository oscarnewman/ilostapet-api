<?php

namespace App\Transformers;

use App\Post;
use App\ContactInfo;
use League\Fractal\TransformerAbstract;


class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'pet',
        'contact'
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
            'address'   => $post->address,
            'lat'       => $post->lat,
            'lng'       => $post->lng,
            'created_at'    => (string) $post->created_at,
            'updated_at'    => (string) $post->updated_at,
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

        return $this->item($pet, new PetTransformer);
    }

    /**
     * Include Contact Info
     * @return Leage\Fractal\ItemResource
     */
    public function includeContact(Post $post) {
        $contact = $post->contact;

        return $this->collection($contact, new ContactTransformer());
    }



}
