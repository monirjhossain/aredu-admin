<?php

namespace App\Models;

use App\Models\Post\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'status',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_subject', 'subject_id', 'post_id');
    }
}
