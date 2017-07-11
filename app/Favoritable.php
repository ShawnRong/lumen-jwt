<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorite()
    {
        $attributes = ['user_id' => app('Dingo\Api\Auth\Auth')->user()->id];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => app('Dingo\Api\Auth\Auth')->user()->id];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', app('Dingo\Api\Auth\Auth')->user()->id)->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
