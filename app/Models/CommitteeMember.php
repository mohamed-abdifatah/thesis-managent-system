<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $guarded = [];

    public function defenseSession()
    {
        return $this->belongsTo(DefenseSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
