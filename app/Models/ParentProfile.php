<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_id',
        'father_name',
        'mother_name',
        'phone',
        'alternate_phone',
        'address',
        'occupation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // Optional: if you have Student model
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}