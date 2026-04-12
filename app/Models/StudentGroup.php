<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    protected $guarded = [];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class, 'student_group_id');
    }
}
