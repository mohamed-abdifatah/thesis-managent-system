<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Thesis;

class Student extends Model
{
    protected $guarded = [];

    public static function generateStudentIdNumber(int $userId): string
    {
        $base = 'STD' . now()->format('Y') . str_pad((string) $userId, 6, '0', STR_PAD_LEFT);

        if (!static::where('student_id_number', $base)->exists()) {
            return $base;
        }

        $suffix = 1;
        do {
            $candidate = $base . '-' . str_pad((string) $suffix, 2, '0', STR_PAD_LEFT);
            $suffix++;
        } while (static::where('student_id_number', $candidate)->exists());

        return $candidate;
    }

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
            ->where('student_group_id', $this->student_group_id)
            ->latest('id')
            ->first();
    }
}
