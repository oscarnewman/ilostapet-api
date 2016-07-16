<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StorePostRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseAPIController;

use App\Transformers\PetTransformer;

use App\Pet;
use App\Image;

class PetController extends BaseAPIController
{
    public function __construct() {
        $this->middleware('api.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->auth->user();
        $pets = $user->pets()->get();

        return $this->response->collection($pets, new PetTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePetRequest $request)
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

        if ($request->has('queued_images')) {
            $queued_images = $request->get('queued_images');
            foreach($queued_images as $queued) {
                $pet->images()->save(Image::hashID($queued));
            }
        }

        return $this->response->created(route('pets.show', ['id' => $pet->hash_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pet = Pet::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        return $this->response->item($pet, new PetTransformer());
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
        $pet = Pet::hashID($id, function() {
            return $this->response->errorNotFound();
        });

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

        if ($request->has('queued_images')) {
            $pet->images->map(function($item, $key) {
                $image->deleteS3();
                $image->delete();
            });

            $queued_images = $request->get('queued_images');
            foreach($queued_images as $queued) {
                $pet->images()->save(Image::hashID($queued));
            }
        }

        return $this->response->created(route('pets.show', ['id' => $pet->hash_id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pet = Pet::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        $pet->delete();
    }
}
