<?php

namespace App\Models\Pizza;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrocaIngrediente extends Model
{
    use HasFactory;
    protected $table = 'trocas_ativas';
    protected $fillable = ['twitch_id','id_ingrediente','quantidade'];

}
