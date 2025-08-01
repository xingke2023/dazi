<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGroup extends Model
{
    protected $fillable = [
        'user_id',
        'group_id',
        'joined_at',
        'paid_amount',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'paid_amount' => 'decimal:2',
        'joined_at' => 'datetime',
    ];

    /**
     * 获取用户
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取群组
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(WechatGroup::class, 'group_id');
    }

    /**
     * 获取用户的活跃群组
     */
    public static function getUserActiveGroups($userId)
    {
        return static::where('user_id', $userId)->where('is_active', true)->with('group')->get();
    }

    /**
     * 获取群组的活跃成员
     */
    public static function getGroupActiveMembers($groupId)
    {
        return static::where('group_id', $groupId)->where('is_active', true)->with('user')->get();
    }

    /**
     * 用户加入群组
     */
    public static function joinGroup($userId, $groupId, $paidAmount = 0)
    {
        $userGroup = static::firstOrCreate(
            ['user_id' => $userId, 'group_id' => $groupId],
            [
                'joined_at' => now(),
                'paid_amount' => $paidAmount,
                'is_active' => true,
            ]
        );

        if (!$userGroup->wasRecentlyCreated && !$userGroup->is_active) {
            $userGroup->update([
                'joined_at' => now(),
                'paid_amount' => $paidAmount,
                'is_active' => true,
            ]);
        }

        // 更新群组成员数量
        $userGroup->group->updateMemberCount();

        return $userGroup;
    }

    /**
     * 用户退出群组
     */
    public function leaveGroup()
    {
        $this->update(['is_active' => false]);
        
        // 更新群组成员数量
        $this->group->updateMemberCount();
        
        return $this;
    }

    /**
     * 检查用户是否已加入群组
     */
    public static function isUserInGroup($userId, $groupId)
    {
        return static::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->where('is_active', true)
            ->exists();
    }
}
