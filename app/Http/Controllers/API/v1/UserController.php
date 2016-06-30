<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use Hash;

use App\Http\Requests;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseAPIController;

use App\Transformers\UserTransformer;
use App\User;

class UserController extends BaseAPIController
{

    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['store']]);
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $input = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => Hash::make($request->input('password')),
        ];

        if (!$user = User::create($input)) {
            return $this->response->errorInternal('Error creating user');
        }
        return $this->response->created(route('api.users.show', ['id' => $user->hash_id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        if ($user->id != $this->auth->user()->id) {
            return $this->response->errorUnauthorized();
        }
        return $this->response->item($user, new UserTransformer, ['key' => 'user']);
        // return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $input = [
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
        ];

        if ($request->has('password')) {
            $input['password'] = Hash::make($request->input('password'));
        }

        $user = User::hashID($id, function() {
            return $this->response->errorNotFound();
        });

        if ($user->id != $this->auth->user()->id) {
            return $this->response->errorUnauthorized();
        }

        if (!$user->update($input)) {
            return $this->response->errorInternal('Error updating user');
        }
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->auth->user();
        $user->destroy();

        return $this->response->noContent();
    }
}
