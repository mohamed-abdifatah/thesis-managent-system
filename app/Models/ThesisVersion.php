<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisVersion extends Model
{
    protected $guarded = [];

    public const STATUSES = [
        'draft',
        'reviewed',
        'needs_changes',
        'approved',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
