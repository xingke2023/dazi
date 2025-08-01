<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Region extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 获取上级地区
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    /**
     * 获取子级地区
     */
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    /**
     * 获取该地区的微信群
     */
    public function wechatGroups(): HasMany
    {
        return $this->hasMany(WechatGroup::class, 'province_id');
    }

    /**
     * 获取启用的子级地区
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')->where('is_active', true);
    }

    /**
     * 获取省份
     */
    public static function getProvinces()
    {
        return static::where('level', 1)->where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * 获取城市
     */
    public static function getCitiesByProvince($provinceId)
    {
        return static::where('level', 2)->where('parent_id', $provinceId)->where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * 获取县区
     */
    public static function getDistrictsByCity($cityId)
    {
        return static::where('level', 3)->where('parent_id', $cityId)->where('is_active', true)->orderBy('sort_order')->get();
    }
}
