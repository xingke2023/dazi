<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 广州市县区
        $guangzhouDistricts = [
            ['name' => '荔湾区', 'code' => '440103', 'parent_id' => 233, 'sort_order' => 1],
            ['name' => '越秀区', 'code' => '440104', 'parent_id' => 233, 'sort_order' => 2],
            ['name' => '海珠区', 'code' => '440105', 'parent_id' => 233, 'sort_order' => 3],
            ['name' => '天河区', 'code' => '440106', 'parent_id' => 233, 'sort_order' => 4],
            ['name' => '白云区', 'code' => '440111', 'parent_id' => 233, 'sort_order' => 5],
            ['name' => '黄埔区', 'code' => '440112', 'parent_id' => 233, 'sort_order' => 6],
            ['name' => '番禺区', 'code' => '440113', 'parent_id' => 233, 'sort_order' => 7],
            ['name' => '花都区', 'code' => '440114', 'parent_id' => 233, 'sort_order' => 8],
            ['name' => '南沙区', 'code' => '440115', 'parent_id' => 233, 'sort_order' => 9],
            ['name' => '从化区', 'code' => '440117', 'parent_id' => 233, 'sort_order' => 10],
            ['name' => '增城区', 'code' => '440118', 'parent_id' => 233, 'sort_order' => 11],
        ];

        // 深圳市县区
        $shenzhenDistricts = [
            ['name' => '罗湖区', 'code' => '440303', 'parent_id' => 235, 'sort_order' => 1],
            ['name' => '福田区', 'code' => '440304', 'parent_id' => 235, 'sort_order' => 2],
            ['name' => '南山区', 'code' => '440305', 'parent_id' => 235, 'sort_order' => 3],
            ['name' => '宝安区', 'code' => '440306', 'parent_id' => 235, 'sort_order' => 4],
            ['name' => '龙岗区', 'code' => '440307', 'parent_id' => 235, 'sort_order' => 5],
            ['name' => '盐田区', 'code' => '440308', 'parent_id' => 235, 'sort_order' => 6],
            ['name' => '龙华区', 'code' => '440309', 'parent_id' => 235, 'sort_order' => 7],
            ['name' => '坪山区', 'code' => '440310', 'parent_id' => 235, 'sort_order' => 8],
            ['name' => '光明区', 'code' => '440311', 'parent_id' => 235, 'sort_order' => 9],
            ['name' => '大鹏新区', 'code' => '440312', 'parent_id' => 235, 'sort_order' => 10],
        ];

        // 插入广州市县区
        foreach ($guangzhouDistricts as $district) {
            DB::table('regions')->insert([
                'name' => $district['name'],
                'code' => $district['code'],
                'level' => 3,
                'parent_id' => $district['parent_id'],
                'sort_order' => $district['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 插入深圳市县区
        foreach ($shenzhenDistricts as $district) {
            DB::table('regions')->insert([
                'name' => $district['name'],
                'code' => $district['code'],
                'level' => 3,
                'parent_id' => $district['parent_id'],
                'sort_order' => $district['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 删除广州市县区
        DB::table('regions')->where('parent_id', 233)->where('level', 3)->delete();
        
        // 删除深圳市县区
        DB::table('regions')->where('parent_id', 235)->where('level', 3)->delete();
    }
};
