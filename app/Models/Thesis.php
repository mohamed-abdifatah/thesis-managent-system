<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function versions()
    {
        return $this->hasMany(ThesisVersion::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function defense()
    {
        return $this->hasOne(DefenseSession::class);
    }
}
