<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
   use SoftDeletes;
//mendaftarkan detail data column agar data tersebt bisa diisi
    protected $fillable = ['title', 'genre', 'duration', 'director', 'description', 'age_rating', 'poster', 'actived'];


    public function schedules()
    { //hasmany() : one to many
      //hasOne() : one to one
        return $this->hasMany(Schedule::class);
    }
}
