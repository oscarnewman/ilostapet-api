<?php

use App\User;
use App\Pet;
use App\Post;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestSeeRecentPosts extends TestCase
{

    // use DatabaseMigrations;

    function __construct()
    {
        parent::setUp();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSeeRecentPosts()
    {
        $pet = factory(Pet::class)->create(['breed' => 'Boxer post test']);
        $post = factory(Post::class)->create(["pet_id" => $pet->id]);

        $this->visit('/api/posts')
             ->see('Boxer post test');
            //  ->seeJson(['hash_id' => $post->hash_id]);
    }
}
