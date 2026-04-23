<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSCAExam extends Model
{
    use HasFactory;

    protected $table = 'csca_exams';
    protected $fillable = ['name'];

    public function studentResults()
    {
        return $this->hasMany(StudentCSCAExam::class);
    }
}
