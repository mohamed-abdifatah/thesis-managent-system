<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisVersion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_final_thesis' => 'boolean',
        'finalized_at' => 'datetime',
    ];

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

    public function unit()
    {
        return $this->belongsTo(ThesisUnit::class, 'thesis_unit_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getUnitSequenceAttribute(): int
    {
        return (int) ($this->unit_number ?? $this->version_number);
    }

    public function getUnitLabelAttribute(): string
    {
        $unitName = trim((string) ($this->unit?->name ?? ''));
        $sequence = $this->unit_sequence;

        if ($unitName !== '') {
            if (preg_match('/\b(\d+)$/', $unitName, $matches) && (int) $matches[1] === $sequence) {
                return $unitName;
            }

            return $unitName . ' ' . $sequence;
        }

        return 'Unit ' . $sequence;
    }
}
