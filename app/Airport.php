<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    //

    public function flights(){
        //return $this->hasMany(Task::class);return $this->belongsTo('App\User', 'foreign_key', 'other_key');

        return $this->hasMany(App\Flight::class);
    }
}
