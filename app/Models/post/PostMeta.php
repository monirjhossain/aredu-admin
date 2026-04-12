<?php

namespace App\Models\Post;

use App\Models\Category;
use App\Models\Post\Post;
use App\Models\Student\Student;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PostMeta extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;

    protected $table = 'post_metas';
    protected $fillable = [
        'post_id',
        'meta_key',
        'meta_value',
    ];

    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
}
