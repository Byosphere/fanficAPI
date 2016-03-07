<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = ['titre', 'reference'];

    public function author(){

        return $this->hasOne('App\User');
    }

    public function pages() {

        return $this->hasMany('App\Page');
    }

    public function lecteurs() {

        return $this->belongsToMany('App\User')->withPivot('pageActuelle');
    }
}
