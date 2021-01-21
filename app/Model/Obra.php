<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    protected  $table='obra';

    protected $fillable = [
        'name','image','public_id','width', 'height'
    ];

    //categoria
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    //tematica
    public function tematica(){
        return $this->belongsTo(Tematica::class);
    }

    //tecnica
    public function tecnica(){
        return $this->belongsTo(Tecnica::class);
    }

    //soporte
    public function soporte(){
        return $this->belongsTo(Soporte::class);
    }
}
