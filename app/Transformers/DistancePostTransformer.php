<?php

namespace App\Transformers;

use App\Post;
use App\PostDistanceResult;
use League\Fractal\TransformerAbstract;

class DistancePostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'post'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(PostDistanceResult $result)
    {
        // $post = $result->post;
        // $pet = $post->pet;

        return [
            'distance' => $result->distance,
        ];
    }

    /**
     * Include Pet
     *
     * @return League\Fractal\ItemResource
     */
    public function includePost(PostDistanceResult $result)
    {
        $post = Post::find($result->post_id);

        return $this->item($post, new PostTransformer, 'post');
    }

}
