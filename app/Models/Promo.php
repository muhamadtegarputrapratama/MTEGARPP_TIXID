<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'promo_code',
        'discount',
        'type',
        'actived',
    ];

      public function tickets() {
        return $this->hasMany(Ticket::class);
    }
    
}
