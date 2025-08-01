<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeChatGroupSeeder extends Seeder
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

        // 先检查或插入地区数据（广东省和城市）
        $province = DB::table('regions')->where('code', '440000')->first();
        if (!$province) {
            $provinceId = DB::table('regions')->insertGetId([
                'name' => '广东省',
                'code' => '440000',
                'level' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $provinceId = $province->id;
        }

        $cities = [
            ['name' => '广州市', 'code' => '440100'],
            ['name' => '深圳市', 'code' => '440300'],
            ['name' => '珠海市', 'code' => '440400'],
            ['name' => '汕头市', 'code' => '440500'],
            ['name' => '佛山市', 'code' => '440600'],
            ['name' => '韶关市', 'code' => '440200'],
            ['name' => '湛江市', 'code' => '440800'],
            ['name' => '肇庆市', 'code' => '441200'],
            ['name' => '江门市', 'code' => '440700'],
            ['name' => '茂名市', 'code' => '440900'],
            ['name' => '惠州市', 'code' => '441300'],
            ['name' => '梅州市', 'code' => '441400'],
            ['name' => '汕尾市', 'code' => '441500'],
            ['name' => '河源市', 'code' => '441600'],
            ['name' => '阳江市', 'code' => '441700'],
            ['name' => '清远市', 'code' => '441800'],
            ['name' => '东莞市', 'code' => '441900'],
            ['name' => '中山市', 'code' => '442000'],
            ['name' => '潮州市', 'code' => '445100'],
            ['name' => '揭阳市', 'code' => '445200'],
            ['name' => '云浮市', 'code' => '445300'],
        ];

        $cityIds = [];
        foreach ($cities as $city) {
            $existingCity = DB::table('regions')->where('code', $city['code'])->first();
            if (!$existingCity) {
                $cityIds[] = DB::table('regions')->insertGetId([
                    'name' => $city['name'],
                    'code' => $city['code'],
                    'level' => 2,
                    'parent_id' => $provinceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $cityIds[] = $existingCity->id;
            }
        }

        // 生成群组数据
        $groups = [];
        $groupNumber = 1;

        foreach ($cityIds as $index => $cityId) {
            $cityName = $cities[$index]['name'];
            
            // 每个城市创建8-15个群
            $groupCount = rand(8, 15);
            
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
                $groupName = str_replace('市', '', $cityName) . $category;
                
                // 生成群编号
                $groupNumberStr = 'WX' . str_pad($groupNumber, 6, '0', STR_PAD_LEFT);
                
                // 随机生成成员数量
                $capacity = rand(200, 500);
                $currentMembers = rand(50, $capacity - 50);
                $paidMembers = rand(0, $currentMembers);
                
                // 统一价格9.9元
                $price = 9.9;
                
                // 随机是否热门
                $isHot = rand(0, 4) == 0; // 20% 概率为热门
                
                $groups[] = [
                    'province_id' => $provinceId,
                    'city_id' => $cityId,
                    'district_id' => null,
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
                    'description' => "欢迎加入{$groupName}，这里聚集了{$cityName}的优质伙伴，入群费用{$price}元，一起分享生活，寻找志同道合的朋友！",
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
        
        $this->command->info('已成功创建 ' . count($groups) . ' 个群组数据');
    }
}