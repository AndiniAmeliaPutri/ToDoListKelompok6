<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Label prioritas dalam Bahasa Indonesia
     */
    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
            default => 'Sedang',
        };
    }

    /**
     * Bootstrap badge color class untuk prioritas
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'bg-danger text-white',
            'medium' => 'bg-warning text-dark',
            'low' => 'bg-info text-dark',
            default => 'bg-secondary text-white',
        };
    }

    /**
     * Cek apakah tugas sudah lewat deadline
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->is_completed || ! $this->due_date) {
            return false;
        }

        return $this->due_date->isPast() && ! $this->due_date->isToday();
    }

    /**
     * Status tenggat waktu (human-readable)
     */
    public function getDueStatusAttribute(): array
    {
        if ($this->is_completed) {
            return [
                'text' => 'Selesai',
                'class' => 'text-success',
                'badge' => 'bg-success-subtle text-success border border-success-subtle',
                'icon' => 'bi-check2-circle',
            ];
        }

        if (! $this->due_date) {
            return [
                'text' => 'Tanpa Deadline',
                'class' => 'text-muted',
                'badge' => 'bg-light text-secondary border',
                'icon' => 'bi-calendar-x',
            ];
        }

        if ($this->is_overdue) {
            return [
                'text' => 'Terlewat (' . $this->due_date->format('d M Y') . ')',
                'class' => 'text-danger font-semibold',
                'badge' => 'bg-danger-subtle text-danger border border-danger-subtle',
                'icon' => 'bi-exclamation-triangle-fill',
            ];
        }

        if ($this->due_date->isToday()) {
            return [
                'text' => 'Hari Ini',
                'class' => 'text-warning font-semibold',
                'badge' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'icon' => 'bi-clock-fill',
            ];
        }

        if ($this->due_date->isTomorrow()) {
            return [
                'text' => 'Besok',
                'class' => 'text-primary',
                'badge' => 'bg-primary-subtle text-primary border border-primary-subtle',
                'icon' => 'bi-calendar-event',
            ];
        }

        return [
            'text' => $this->due_date->format('d M Y'),
            'class' => 'text-secondary',
            'badge' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
            'icon' => 'bi-calendar3',
        ];
    }
}
