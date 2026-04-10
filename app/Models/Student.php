<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Thesis;

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

    public function accessibleThesis(): ?Thesis
    {
        $ownThesis = $this->thesis()->latest('id')->first();
        if ($ownThesis) {
            return $ownThesis;
        }

        if (!$this->student_group_id) {
            return null;
        }

        return Thesis::query()
            ->whereHas('student', function ($query) {
                $query->where('student_group_id', $this->student_group_id);
            })
            ->latest('id')
            ->first();
    }
}
