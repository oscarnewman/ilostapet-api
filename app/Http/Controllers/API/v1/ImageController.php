<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Image;
use Intervention;
use Storage;
use Validator;

use App\Transformers\ImageTransformer;

class ImageController extends BaseAPIController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $images = $request->file('images');
        $final_images = collect();

        foreach($images as $image) {
            $v = Validator::make(
                ['image' => $image],
                ['image' => 'required|image|max:15000']
            );

            if($v->fails()) {
                return $this->response->errorBadRequest(['errors' => $v->errors()]);
            }

            $final_images->push(Image::prepareAndCreate($image));
        }

        return $this->response->collection($final_images, new ImageTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        try {
            $image->deleteS3();
        } catch (Exception $e) {
            return $this->response->errorInternal('Could not delete image.');
        }
        $image->delete();

        return $this->response->noContent();
    }
}
