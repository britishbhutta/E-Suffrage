<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'module',
    ];
    public function users()
        {
            return $this->BelongsTo(User::class, 'user_id','id');
        }
}
