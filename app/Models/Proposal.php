<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $guarded = [];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }
}
