<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    //
    protected $fillable = ['user_id','competitor_hotel_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
