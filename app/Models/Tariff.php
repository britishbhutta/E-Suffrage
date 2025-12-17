<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = ['title', 'description', 'note', 'features', 'price_cents', 'currency','available_votes' ];
     protected $casts = [
    'features' => 'array',
    ];
    public function booking()
    {
        return $this->hasMany(Booking::class, 'tariff_id', 'id');
    }
   

}
