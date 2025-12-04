<?php

namespace App\Models;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    protected $fillable = [
        'name',
        'logo',
        'email',
        'phone',
        'address',
        'details',
        'remarks'
    ];

    public function users(){
        return $this->belongsToMany(User::class)->orderByDesc('id');
    }
    public function students(){
        return $this->belongsToMany(Student::class)->orderByDesc('id');
    }

}
