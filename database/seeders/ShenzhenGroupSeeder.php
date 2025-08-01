<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShenzhenGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 定义标签选项
        $tagOptions = [
            '交友', '脱单', '征婚', '美食搭子', '运动健身', 
            '旅游', '文艺交流', '职场交流', '学习交流', '游戏娱乐',
            '兴趣爱好', '商务合作', '生活分享', '情感咨询'
        ];

        // 定义群分类
        $categories = [
            '青年交友群', '单身脱单群', '征婚交友群', '美食搭子群', 
            '运动健身群', '旅游交友群', '文艺青年群', '职场精英群',
            '学习交流群', '游戏娱乐群', '兴趣爱好群', '商务合作群'
        ];

        // 获取深圳市
        $shenzhenCity = DB::table('regions')->where('code', '440300')->first();
        if (!$shenzhenCity) {
            $this->command->error('未找到深圳市数据');
            return;
        }

        // 深圳主要区县数据
        $shenzhenDistricts = [
            ['name' => '福田区', 'code' => '440304'],
            ['name' => '罗湖区', 'code' => '440303'],
            ['name' => '南山区', 'code' => '440305'],
            ['name' => '宝安区', 'code' => '440306'],
            ['name' => '龙岗区', 'code' => '440307'],
            ['name' => '盐田区', 'code' => '440308'],
            ['name' => '龙华区', 'code' => '440309'],
            ['name' => '坪山区', 'code' => '440310'],
            ['name' => '光明区', 'code' => '440311'],
            ['name' => '大鹏新区', 'code' => '440312'],
        ];

        // 先创建或获取区县数据
        $districtIds = [];
        foreach ($shenzhenDistricts as $district) {
            $existing = DB::table('regions')->where('code', $district['code'])->first();
            if (!$existing) {
                $districtId = DB::table('regions')->insertGetId([
                    'name' => $district['name'],
                    'code' => $district['code'],
                    'level' => 3,
                    'parent_id' => $shenzhenCity->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $districtIds[] = ['id' => $districtId, 'name' => $district['name']];
                $this->command->info('创建区县: ' . $district['name']);
            } else {
                $districtIds[] = ['id' => $existing->id, 'name' => $district['name']];
                $this->command->info('区县已存在: ' . $district['name']);
            }
        }

        // 生成群组数据
        $groups = [];
        $groupNumber = 2000; // 从2000开始编号避免冲突

        foreach ($districtIds as $district) {
            $districtName = $district['name'];
            $districtId = $district['id'];
            
            // 每个区创建4-6个群
            $groupCount = rand(4, 6);
            
            for ($i = 1; $i <= $groupCount; $i++) {
                // 随机选择分类
                $category = $categories[array_rand($categories)];
                
                // 随机选择2-4个标签
                $selectedTags = array_rand($tagOptions, rand(2, 4));
                if (!is_array($selectedTags)) {
                    $selectedTags = [$selectedTags];
                }
                $tags = implode(',', array_map(function($index) use ($tagOptions) {
                    return $tagOptions[$index];
                }, $selectedTags));

                // 生成群昵称
                $nickname = "群{$i}";
                
                // 生成群名称
                $groupName = str_replace('区', '', $districtName) . $category;
                
                // 生成群编号
                $groupNumberStr = 'SZ' . str_pad($groupNumber, 6, '0', STR_PAD_LEFT);
                
                // 随机生成成员数量
                $capacity = rand(200, 500);
                $currentMembers = rand(50, $capacity - 50);
                $paidMembers = rand(0, $currentMembers);
                
                // 统一价格9.9元
                $price = 9.9;
                
                // 随机是否热门
                $isHot = rand(0, 4) == 0; // 20% 概率为热门
                
                $groups[] = [
                    'province_id' => $shenzhenCity->parent_id, // 广东省ID
                    'city_id' => $shenzhenCity->id,
                    'district_id' => $districtId,
                    'category_name' => $category,
                    'group_name' => $groupName,
                    'nickname' => $nickname,
                    'qr_code_logo' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://example.com/group/' . $groupNumber,
                    'group_number' => $groupNumberStr,
                    'capacity' => $capacity,
                    'current_members' => $currentMembers,
                    'paid_members' => $paidMembers,
                    'is_hot' => $isHot,
                    'price' => $price,
                    'description' => "欢迎加入{$groupName}，这里聚集了{$districtName}的优质伙伴，入群费用{$price}元，一起分享生活，寻找志同道合的朋友！",
                    'tags' => $tags,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $groupNumber++;
            }
        }

        // 批量插入群组数据
        DB::table('wechat_groups')->insert($groups);
        
        $this->command->info('已成功创建 ' . count($groups) . ' 个深圳群组数据');
    }
}