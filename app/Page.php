<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['texte', 'numPage'];

    public function story() {

        return $this->belongsTo('App\Story', 'story_id');
    }

}
