<?php

namespace App;


class PostDistanceResult
{
    public $post_id;
    public $distance = 0;

    public function __construct($post_id, $distance) {
        $this->post_id = $post_id;
        $this->distance = $distance;
    }

}
