<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Soporte extends Model
{
    protected  $table='soporte';

    protected $fillable = [
        'name'
    ];

    //obras
    public function obras(){
        return $this->hasMany(Obra::class);
    }
}
