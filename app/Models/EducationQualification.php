<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationQualification extends Model
{
    use HasFactory;
    protected $fillable = [
        'education_level_id',
        'student_id',
        'institution',
        'group_subject',
        'result',
        'out_of',
        'passing_year',
        'attend_from',
        'attend_to',
        'country',
        'remarks'
    ];

}
