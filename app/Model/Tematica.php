<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tematica extends Model
{
    protected  $table='tematica';

    protected $fillable = [
        'name'
    ];

    //obras
    public function obras(){
        return $this->hasMany(Obra::class);
    }
}
