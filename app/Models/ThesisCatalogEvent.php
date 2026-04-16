<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisCatalogEvent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
