<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelBasicData extends Model
{
    //
    protected $table = 'hotels_basic_data';
    protected $primaryKey = 'uid';
    public $incrementing = false;
}
