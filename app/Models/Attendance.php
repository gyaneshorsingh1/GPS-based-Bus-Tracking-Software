<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'bus_id',
        'date',
        'check_in_at',
        'check_out_at',
        'marked_by',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function isCheckedIn(): bool
    {
        return $this->check_in_at !== null;
    }

    public function isCheckedOut(): bool
    {
        return $this->check_out_at !== null;
    }
}
