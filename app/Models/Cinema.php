<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //daftarin softdelete
    use SoftDeletes;
//mendaftarkan detail data column agar data tersebt bisa diisi
    protected $fillable = ['name', 'location'];

    //definisikan relasi, kaerna ke schedule nya itu many jadi jamak (S)
    public function schedules()
    { //hasmany() : one to many
      //hasOne() : one to one
        return $this->hasMany(Schedule::class);
    }
}
