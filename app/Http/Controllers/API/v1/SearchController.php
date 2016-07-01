<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transformers\DistancePostTransformer;
use App\Post;
use App\PostDistanceResult;

class SearchController extends BaseAPIController
{
    public function byLocation($lat, $lng) {
        // Calculate posts in bounding box
        $range = env('SEARCH_RADIUS_MI')/10;
        $posts =    Post::whereBetween('lat', [$lat - $range, $lat + $range])
                        ->whereBetween('lng', [$lng - $range, $lng + $range])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // return $posts;

        $geotools = new \League\Geotools\Geotools();
        $userCoord   = new \League\Geotools\Coordinate\Coordinate([$lat, $lng]);

        $results = collect();

        foreach($posts as $post) {
            $postCoord   = new \League\Geotools\Coordinate\Coordinate([$post->lat, $post->lng]);
            $distance = $geotools->distance()->setFrom($userCoord)->setTo($postCoord);
            $result = $distance->in('mi')->flat();
            $results->push(new PostDistanceResult($post->id, $result));
        }

        // usort($results, function($a, $b) {
        //     return $a['distance'] - $b['distance'];
        // });
        //
        $sorted = $results->sortBy('distance');

        return $this->response->collection($sorted, new DistancePostTransformer());


    }
}
