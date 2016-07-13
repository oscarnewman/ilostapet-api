<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;

use JWTAuth;

use App\Http\Requests;
use App\Http\Requests\APIAuthLoginRequest;
use App\Http\Requests\APIAuthRefreshRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseAPIController;

use App\Transformers\UserTransformer;
use App\RefreshToken;

class SessionController extends BaseAPIController
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
    public function store(APIAuthLoginRequest $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized('Credientials do not match');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->errorInternal('Could not create token');
        }

        $refresh_token = RefreshToken::create($token);

        // all good so return the token
        return response()->json([
            'token' => $token,
            'refresh_token' => $refresh_token,
        ]);
    }

    /**
     * Display the current session.
     *
     * @return \Illuminate\Http\Response
     */
    public function current()
    {
        $user = $this->auth->user();
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(APIAuthRefreshRequest $request)
    {
        $token = JWTAuth::getToken();
        $refresh_token = $request->input('refresh_token');

        if (!RefreshToken::check($token, $refresh_token)) {
            return $this->response->errorBadRequest('Token and refresh token do not match');
        }

        RefreshToken::delete($token);

        // Generate new Token & Refresh Token
        $token = JWTAuth::refresh();
        $refresh_token = RefreshToken::create($token);

        return $this->response->array([
            'token' => $token,
            'refresh_token' => $refresh_token,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response->noContent();
    }
}
