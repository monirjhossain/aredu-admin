<?php

namespace App\Models\Inquiry;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Traits\ModelSearchAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    use HasFactory, ModelSearchAble, SoftDeletes;
    
    protected $table = 'inquiries';
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_no',
        'country',
        'country_preferred',
        'course',
        'last_education',
        'email',
        'oppurtunity',
        'message',
        'status',
        'view',
    ];

    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
