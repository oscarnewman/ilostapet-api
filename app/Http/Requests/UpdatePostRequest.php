<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdatePostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'          => 'required|in:lost,found',
            'address'       => 'required',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric',
            // Pet fields, included here for convenience
            'animal'        => 'required|in:dog,cat',
            'breed'         => 'required',
            'gender'        => 'required|in:male,female',
            'description'   => 'required',
            'name'          => 'required',
            'has_collar'    => 'required|boolean',
            'has_tags'      => 'required|boolean',
            'has_microchip' => 'required|boolean',
        ];
    }
}
