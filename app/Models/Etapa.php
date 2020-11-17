<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $table = 'etapa';

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function datoSeguimientos()
    {
        return $this->hasMany(DatoSeguimiento::class);
    }
}
