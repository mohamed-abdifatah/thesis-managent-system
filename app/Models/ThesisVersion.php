<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisVersion extends Model
{
    protected $guarded = [];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }
}
