<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseAPIController;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use App\Transformers\PostTransformer;

use App\Post;
use App\Pet;
use App\Image;

class PostController extends BaseAPIController
{
    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(20);
        return $this->response->paginator($posts, new PostTransformer, ['key' => 'posts']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $user = $this->auth->user();

        $pet_data = [
            'animal' => $request->get('animal'),
            'gender' => $request->get('gender'),
            'name' => $request->get('name'),
            'breed' => $request->get('breed'),
            'description' => $request->get('description'),
            'has_collar' => $request->get('has_collar'),
            'has_tags' => $request->get('has_tags'),
            'has_microchips' => $request->get('has_microchips'),
            'user_id'   => $user->id,
        ];

        if(! $pet = Pet::create($pet_data)) {
            return $this->response->errorInternal('Could not create post. Please try again.');
        }

        $post_data = [
            'type' => $request->get('type'),
            'address' => $request->get('address'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'pet_id' => $pet->id,
        ];

        if(! $post = Post::create($post_data)) {
            return $this->response->errorInternal('Could not create post. Please try again.');
        }

        if($request->has('phones')) {
            foreach($request->input('phones') as $phone) {
                if($phone == "") break;
                $post->contactInfo()->create([
                    'value' => $phone
                ]);
            }
        }

        if ($request->has('queued_images')) {
            $queued_images = $request->get('queued_images');
            foreach($queued_images as $queued) {
                $post->pet->images()->save(Image::hashID($queued));
            }
        }

        return $this->response->created(route('posts.show', ['id' => $post->hash_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        return $this->response->item($post, new PostTransformer, ['key' => 'post']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        $pet = $post->pet;
        $user = $this->auth->user();

        $pet_data = [
            'animal' => $request->get('animal'),
            'gender' => $request->get('gender'),
            'name' => $request->get('name'),
            'breed' => $request->get('breed'),
            'description' => $request->get('description'),
            'has_collar' => $request->get('has_collar'),
            'has_tags' => $request->get('has_tags'),
            'has_microchips' => $request->get('has_microchips'),
            'user_id'   => $user->id,
        ];

        $pet->update($pet_data);

        $post_data = [
            'type' => $request->get('type'),
            'address' => $request->get('address'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'pet_id' => $pet->id,
        ];

        $post->update($post_data);

        if($request->has('phones')) {
            // Remvoe old phone values
            $post->contactInfo()->map(function($item, $key) {
                $item->delete();
            });

            foreach($request->input('phones') as $phone) {
                if($phone == "") break;
                $post->contactInfo()->create([
                    'value' => $phone
                ]);
            }
        }

        if ($request->has('queued_images')) {
            $post->pet->images->map(function($item, $key) {
                $image->deleteS3();
                $image->delete();
            });

            $queued_images = $request->get('queued_images');
            foreach($queued_images as $queued) {
                $post->pet->images()->save(Image::hashID($queued));
            }
        }


        return $this->response->item($post, new PostTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        $post->delete();
    }
}
