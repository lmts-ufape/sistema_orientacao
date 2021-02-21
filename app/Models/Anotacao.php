<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotacao extends Model
{
    use HasFactory;

    public function anotacoes(){
        return $this->hasMany('App\Models\Anotacao');
    }
}