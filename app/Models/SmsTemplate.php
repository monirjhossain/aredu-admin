<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmsTemplate extends Model
{
    use HasFactory;
    protected $table = 'sms_templates';
    protected $fillable = [
        'branch_id',
        'parent_id',
        'name',
        'message'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            if (Auth::check() && empty($student->branch_id)) {
                $student->branch_id = Auth::user()->branch;
            }
        });
    }
}
