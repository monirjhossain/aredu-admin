<?php

namespace App\Models;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'parent_id',
        'name'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'status', 'id');
    }
}
