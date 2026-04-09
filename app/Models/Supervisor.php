<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function groups()
    {
        return $this->hasMany(StudentGroup::class);
    }
}
