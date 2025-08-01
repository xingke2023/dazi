<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wechat_openid',
        'wechat_nickname',
        'wechat_avatar',
        'avatar',
        'email_verified_at',
        'phone',
        'nickname',
        'referrer_id',
        'address',
        'dating_requirements',
        'hobbies',
        'gender',
        'birth_date',
        'id_card',
        'paid_amount',
        'member_level',
        'notes',
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
            'birth_date' => 'date',
            'paid_amount' => 'decimal:2',
        ];
    }

    /**
     * 获取推荐人
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * 获取被推荐的用户
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    /**
     * 获取用户的群组
     */
    public function userGroups()
    {
        return $this->hasMany(UserGroup::class);
    }

    /**
     * 获取用户的活跃群组
     */
    public function activeGroups()
    {
        return $this->hasMany(UserGroup::class)->where('is_active', true);
    }

    /**
     * 获取用户加入的微信群
     */
    public function wechatGroups()
    {
        return $this->belongsToMany(WechatGroup::class, 'user_groups', 'user_id', 'group_id')
            ->withPivot('joined_at', 'paid_amount', 'is_active', 'notes')
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }
}
