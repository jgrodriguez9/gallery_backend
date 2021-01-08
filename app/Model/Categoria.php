<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected  $table='categoria';

    protected $fillable = [
        'name'
    ];

    //obras
    public function obras(){
        return $this->hasMany(Obra::class);
    }
}
