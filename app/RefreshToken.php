<?php

namespace App;

use Redis;

class RefreshToken
{
    /**
     * Create a refresh token from a token and store it in redis
     * @param  string $token the jwt auth token
     * @return string        the refresh token
     */
    public static function create($token) {
        $key = self::getTokenKey($token);
        $refresh_token = bin2hex(openssl_random_pseudo_bytes(128));
        Redis::set($key, $refresh_token);
        return $refresh_token;
    }

    /**
     * Delete a refresh token for a specific token
     * @param  string $token the jwt auth token
     */
    public static function delete($token) {
        $key = self::getTokenKey($token);
        Redis::del($key);
    }

    /**
     * get a refresh token from a specified token
     * @param  string $token the jwt auth token
     * @return string        the associated refresh token
     */
    public static function find($token) {
        return Redis::get(self::getTokenKey($token));
    }

    /**
     * Determine if the given token and refresh token are associated
     * @param  string $token         the jwt auth token
     * @param  string $refresh_token the refresh token
     * @return bool                  whether $token and $refresh_token are associated
     */
    public static function check($token, $refresh_token) {
        return self::find($token) == $refresh_token;
    }

    /**
     * Get the redis refresh token key for a given token
     * @param  string $token the jwt auth token
     * @return string        the redis key
     */
    private static function getTokenKey($token){
        return "token:$token:refresh";
    }
}
