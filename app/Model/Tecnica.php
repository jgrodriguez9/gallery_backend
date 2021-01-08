<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tecnica extends Model
{
    protected  $table='tecnica';

    protected $fillable = [
        'name'
    ];

    //obras
    public function obras(){
        return $this->hasMany(Obra::class);
    }
}
