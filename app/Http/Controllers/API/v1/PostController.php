<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseAPIController;
use App\Http\Requests\StorePostRequest;

use App\Transformers\PostTransformer;

use App\Post;

class PostController extends BaseAPIController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(20);
        return $this->response->paginator($posts, new PostTransformer, ['key' => 'posts']);
        // return $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $pet_data = [
            'animal' => $request->get('animal'),
            'gender' => $request->get('gender'),
            'name' => $request->get('name'),
            'breed' => $request->get('breed'),
            'description' => $request->get('description'),
            'has_collar' => $request->get('has_collar'),
            'has_tags' => $request->get('has_tags'),
            'has_microchips' => $request->get('has_microchips'),
        ];
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! $post = Post::with('pet')->find($id)) {
            return $this->response->errorNotFound();
        }
        return $this->response->item($post, new PostTransformer, ['key' => 'post']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
