<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefenseSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function committeeMembers()
    {
        return $this->hasMany(CommitteeMember::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
