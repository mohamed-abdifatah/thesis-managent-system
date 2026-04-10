<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $guarded = [];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function thesisVersion()
    {
        return $this->belongsTo(ThesisVersion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
