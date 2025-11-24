<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'name'
    ];
}
