<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jenssegers\Optimus\Optimus;

/**
 * Trait HashIDModel
 * @package App\Traits
 */
trait HashIDModel
{

    /**
     * Scope a query to only include models matching the supplied hash ids.
     * Returns the model by default, or supply a second flag `false` to get the Query Builder instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @param  \Illuminate\Database\Schema\Builder $query The Query Builder instance.
     * @param  string                              $hash_id  The hash id of the model.
     * @param  bool|true                           $first Returns the model by default, or set to `false` to chain for query builder.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function scopeHashID($query, $hash_id, $first = true)
    {
        $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_SPARK'));

        if (!is_numeric($hash_id)) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }
        $id = $optimus->decode($hash_id);
        return $query->find($id);
    }

    /**
     * Scope a query to only include models matching the supplied ID or hash id.
     * Returns the model by default, or supply a second flag `false` to get the Query Builder instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @param  \Illuminate\Database\Schema\Builder $query The Query Builder instance.
     * @param  string                              $hash_id  The hash id of the model.
     * @param  bool|true                           $first Returns the model by default, or set to `false` to chain for query builder.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */

    public function scopeIdOrHashID($query, $id_or_hash_id, $first = true)
    {
        if (!is_numeric($id_or_hash_id)) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        $search = $query->where(function ($query) use ($id_or_hash_id) {
            $query->where('id', $id_or_hash_id)
                ->orWhere('hash_id', $id_or_hash_id);
        });

        return $first ? $search->firstOrFail() : $search;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_SPARK'));
        return $optimus->encode($this->getKey());
    }

    /**
     * Creates computed attribute to get the Hash IDs
     * @return [type] [description]
     */
    public function getHashIdAttribute() {
        $optimus = new Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_SPARK'));
        return $optimus->encode($this->id);
    }

}
