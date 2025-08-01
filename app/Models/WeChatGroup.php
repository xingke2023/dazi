<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeChatGroup extends Model
{
    use HasFactory;

    protected $table = 'wechat_groups';

    protected $fillable = [
        'province_id',
        'city_id',
        'district_id',
        'category_name',
        'group_name',
        'nickname',
        'qr_code_logo',
        'group_logo',
        'group_number',
        'capacity',
        'current_members',
        'paid_members',
        'is_hot',
        'price',
        'description',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'is_hot' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * 获取群组的省份
     */
    public function province()
    {
        return $this->belongsTo(Region::class, 'province_id');
    }

    /**
     * 获取群组的城市
     */
    public function city()
    {
        return $this->belongsTo(Region::class, 'city_id');
    }

    /**
     * 获取群组的区县
     */
    public function district()
    {
        return $this->belongsTo(Region::class, 'district_id');
    }

    /**
     * 获取标签数组
     */
    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    /**
     * 设置标签数组
     */
    public function setTagsArrayAttribute($value)
    {
        $this->attributes['tags'] = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * 获取热门群组
     */
    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    /**
     * 获取活跃群组
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 按省份筛选
     */
    public function scopeByProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * 按城市筛选
     */
    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * 按标签筛选
     */
    public function scopeByTag($query, $tag)
    {
        return $query->where('tags', 'like', '%' . $tag . '%');
    }

    /**
     * 按分类筛选
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category_name', $category);
    }
}