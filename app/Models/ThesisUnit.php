<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisUnit extends Model
{
    protected $guarded = [];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function versions()
    {
        return $this->hasMany(ThesisVersion::class, 'thesis_unit_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
