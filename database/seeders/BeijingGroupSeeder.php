<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeijingGroupSeeder extends Seeder
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

        // 获取北京省份
        $beijingProvince = DB::table('regions')->where('name', '北京市')->first();
        if (!$beijingProvince) {
            $this->command->error('未找到北京省份数据');
            return;
        }

        // 北京主要区县数据
        $beijingDistricts = [
            ['name' => '朝阳区', 'code' => '110105'],
            ['name' => '海淀区', 'code' => '110108'],
            ['name' => '西城区', 'code' => '110102'],
            ['name' => '东城区', 'code' => '110101'],
            ['name' => '丰台区', 'code' => '110106'],
            ['name' => '石景山区', 'code' => '110107'],
            ['name' => '通州区', 'code' => '110112'],
            ['name' => '昌平区', 'code' => '110114'],
        ];

        // 先创建或获取区县数据
        $districtIds = [];
        foreach ($beijingDistricts as $district) {
            $existing = DB::table('regions')->where('code', $district['code'])->first();
            if (!$existing) {
                $districtId = DB::table('regions')->insertGetId([
                    'name' => $district['name'],
                    'code' => $district['code'],
                    'level' => 3,
                    'parent_id' => $beijingProvince->id,
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
        $groupNumber = 1000; // 从1000开始编号避免冲突

        foreach ($districtIds as $district) {
            $districtName = $district['name'];
            $districtId = $district['id'];
            
            // 每个区创建3-4个群
            $groupCount = rand(3, 4);
            
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
                
                // 生成群名称（北京作为直辖市，直接用区名+分类）
                $groupName = str_replace('区', '', $districtName) . $category;
                
                // 生成群编号
                $groupNumberStr = 'BJ' . str_pad($groupNumber, 6, '0', STR_PAD_LEFT);
                
                // 随机生成成员数量
                $capacity = rand(200, 500);
                $currentMembers = rand(50, $capacity - 50);
                $paidMembers = rand(0, $currentMembers);
                
                // 统一价格9.9元
                $price = 9.9;
                
                // 随机是否热门
                $isHot = rand(0, 4) == 0; // 20% 概率为热门
                
                $groups[] = [
                    'province_id' => $beijingProvince->id,
                    'city_id' => $beijingProvince->id, // 北京是直辖市，city_id 设为省份ID
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
        
        $this->command->info('已成功创建 ' . count($groups) . ' 个北京群组数据');
    }
}