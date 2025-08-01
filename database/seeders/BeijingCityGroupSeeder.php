<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeijingCityGroupSeeder extends Seeder
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

        // 获取北京市级和省级ID
        $beijingCity = DB::table('regions')->where('id', 35)->first(); // 北京市级
        $beijingProvince = DB::table('regions')->where('id', 1)->first(); // 北京省级
        
        if (!$beijingCity || !$beijingProvince) {
            $this->command->error('未找到北京市或北京省数据');
            return;
        }

        // 生成5个市级群组
        $cityGroups = [];
        for ($i = 1; $i <= 5; $i++) {
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
            $groupName = "北京市" . $category;
            
            // 生成群编号
            $groupNumber = 3000 + $i;
            $groupNumberStr = 'BJ' . str_pad($groupNumber, 6, '0', STR_PAD_LEFT);
            
            // 随机生成成员数量
            $capacity = rand(300, 500);
            $currentMembers = rand(100, $capacity - 50);
            $paidMembers = rand(0, $currentMembers);
            
            // 统一价格9.9元
            $price = 9.9;
            
            // 市级群组标记为热门
            $isHot = true;
            
            $cityGroups[] = [
                'province_id' => $beijingProvince->id,
                'city_id' => $beijingCity->id,
                'district_id' => null, // 市级群组
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
                'description' => "欢迎加入{$groupName}，这里聚集了北京市的优质伙伴，入群费用{$price}元，一起分享生活，寻找志同道合的朋友！",
                'tags' => $tags,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 批量插入市级群组数据
        DB::table('wechat_groups')->insert($cityGroups);
        
        $this->command->info('已成功创建 ' . count($cityGroups) . ' 个北京市级群组数据');
    }
}