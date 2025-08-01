<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WechatGroup extends Model
{
    protected $fillable = [
        'province_id',
        'city_id',
        'district_id',
        'category_name',
        'group_name',
        'qr_code_logo',
        'group_number',
        'capacity',
        'current_members',
        'paid_members',
        'is_hot',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_hot' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * 获取所属省份
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'province_id');
    }

    /**
     * 获取所属城市
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'city_id');
    }

    /**
     * 获取所属县区
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'district_id');
    }

    /**
     * 获取群成员
     */
    public function userGroups(): HasMany
    {
        return $this->hasMany(UserGroup::class, 'group_id');
    }

    /**
     * 获取活跃的群成员
     */
    public function activeMembers(): HasMany
    {
        return $this->hasMany(UserGroup::class, 'group_id')->where('is_active', true);
    }

    /**
     * 获取热门群组
     */
    public static function getHotGroups()
    {
        return static::where('is_hot', true)->where('is_active', true)->orderBy('paid_members', 'desc')->get();
    }

    /**
     * 按地区获取群组
     */
    public static function getGroupsByRegion($provinceId, $cityId = null, $districtId = null)
    {
        $query = static::where('province_id', $provinceId)->where('is_active', true);
        
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        
        if ($districtId) {
            $query->where('district_id', $districtId);
        }
        
        return $query->orderBy('is_hot', 'desc')->orderBy('paid_members', 'desc')->get();
    }

    /**
     * 按分类获取群组
     */
    public static function getGroupsByCategory($categoryName)
    {
        return static::where('category_name', $categoryName)->where('is_active', true)->orderBy('is_hot', 'desc')->get();
    }

    /**
     * 检查群是否已满
     */
    public function isFull(): bool
    {
        return $this->current_members >= $this->capacity;
    }

    /**
     * 更新群成员数量
     */
    public function updateMemberCount()
    {
        $this->current_members = $this->activeMembers()->count();
        $this->paid_members = $this->activeMembers()->where('paid_amount', '>', 0)->count();
        $this->save();
    }
}
