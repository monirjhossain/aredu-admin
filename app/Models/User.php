<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'username',
        'role_id',
        'related_to',
        'branch',
        'office_id',
        'mobile_no',
        'email',
        'profile_image',
        'email_verified_at',
        'password',
        'profile_pic',
        'signature',
        'remember_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ['full_name'];
    public function getFullNameAttribute() {
        return ucfirst($this->name);
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function todos() {
        return $this->hasMany(Todo::class, 'user_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->isDirty('name')) { // Only regenerate if name changed
                $user->username = static::generateUsername($user->name, $user->id);
            }
        });
    }

    protected static function generateUsername($name, $userId = null)
    {
        // Base username from full name (lowercase, no spaces)
        $username = Str::slug($name, '.');

        $original = $username;
        $counter = 1;

        // Ensure uniqueness
        while (static::where('username', $username)
            ->when($userId, fn($q) => $q->where('id', '!=', $userId))
            ->exists()) {
            $username = $original . $counter++;
        }

        return $username;
    }
}
