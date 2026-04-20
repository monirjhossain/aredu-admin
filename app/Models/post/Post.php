<?php

namespace App\Models\Post;

use App\Models\Category;

use App\Models\Student\Student;
use App\Models\Subject;
use App\Models\Tag;
use App\Models\Post\PostMeta;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;

    protected $table = 'posts';
    protected $fillable = [
        'post_author',
        'post_content',
        'post_title',
        'post_name',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_type',
        'post_mime_type',
        'comment_count',
        'image',
        'status',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute() {
        return  url('/').'/'.$this->image;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            $post->post_name = $post->createSlug($post->post_title);
            $post->save();
        });
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    private function createSlug($title){
        if (static::wherePostName($slug = Str::slug($title))->exists()) {
            $max = static::wherePostTitle($title)->latest('id')->skip(1)->value('post_name');
            if (is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }

            return "{$slug}-2";
        }

        return $slug;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'post_author');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function postMetas()
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class)->orderByDesc('id');
    }
    public function getMeta($key, $default = null)
    {
        $meta = $this->postMetas->firstWhere('meta_key', $key);
        return $meta ? $meta->meta_value : $default;
    }
}
