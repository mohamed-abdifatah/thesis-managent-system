<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_library_approved' => 'boolean',
        'library_approved_at' => 'datetime',
        'is_public' => 'boolean',
        'published_at' => 'datetime',
        'public_downloads' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function group()
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function libraryApprover()
    {
        return $this->belongsTo(User::class, 'library_approved_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function versions()
    {
        return $this->hasMany(ThesisVersion::class);
    }

    public function approvedVersions()
    {
        return $this->hasMany(ThesisVersion::class)->where('status', 'approved');
    }

    public function latestApprovedVersion()
    {
        return $this->hasOne(ThesisVersion::class)
            ->where('status', 'approved')
            ->latestOfMany('version_number');
    }

    public function finalThesisVersion()
    {
        return $this->hasOne(ThesisVersion::class)
            ->where('is_final_thesis', true)
            ->latestOfMany('finalized_at');
    }

    public function units()
    {
        return $this->hasMany(ThesisUnit::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function defense()
    {
        return $this->hasOne(DefenseSession::class);
    }

    public function catalogEvents()
    {
        return $this->hasMany(ThesisCatalogEvent::class)->latest();
    }
}
