<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'rol',
        'profile_picture',
        'password',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'rol' => 'user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile picture URL.
     *
     * @return string
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/media/' . $this->profile_picture);
        }
        
        // Usar placeholder de UI-Avatars si no tiene foto de perfil
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6c757d&color=ffffff&rounded=true";
    }

    /**
     * Get the full path for the profile picture in storage.
     *
     * @return string|null
     */
    public function getProfilePicturePathAttribute()
    {
        if ($this->profile_picture) {
            return storage_path('app/public/media/' . $this->profile_picture);
        }
        
        return null;
    }

    /**
     * Check if user has a profile picture.
     *
     * @return bool
     */
    public function hasProfilePicture()
    {
        return !empty($this->profile_picture);
    }
}
