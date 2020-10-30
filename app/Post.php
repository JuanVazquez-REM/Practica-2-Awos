<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //Aqui le asigno que campos pueden ser llenados,del modelo hacia la BD 
    //creo una variable protegida llamada $rellenable, y le asigno los campos que pueden ser llenados,
    //protected $fillable = ['user_id','titulo','contenido'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comment(){
        return $this->hasMany(Comment::class);
    }
}
