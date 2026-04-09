<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thesis()
    {
        return $this->hasOne(Thesis::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function group()
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }
}
