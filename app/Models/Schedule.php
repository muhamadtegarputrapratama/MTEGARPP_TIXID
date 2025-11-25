<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
     use SoftDeletes;
//mendaftarkan detail data column agar data tersebt bisa diisi
    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

    //json
    //di migration support json,
    protected function casts(): array
    {
        return [
            'hours' => 'array'
        ];
    }

    //karna cinema posisi one jadi tunggal
    public function cinema()
    { //karna scheduule ada di posisi 2 gunakan belongsto untuk menyambungkan
        return $this->belongsTo(Cinema::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

      public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
