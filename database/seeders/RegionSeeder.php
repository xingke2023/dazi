<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            // 省份
            ['name' => '北京市', 'code' => '110000', 'level' => 1, 'parent_id' => null, 'sort_order' => 1],
            ['name' => '天津市', 'code' => '120000', 'level' => 1, 'parent_id' => null, 'sort_order' => 2],
            ['name' => '河北省', 'code' => '130000', 'level' => 1, 'parent_id' => null, 'sort_order' => 3],
            ['name' => '山西省', 'code' => '140000', 'level' => 1, 'parent_id' => null, 'sort_order' => 4],
            ['name' => '内蒙古自治区', 'code' => '150000', 'level' => 1, 'parent_id' => null, 'sort_order' => 5],
            ['name' => '辽宁省', 'code' => '210000', 'level' => 1, 'parent_id' => null, 'sort_order' => 6],
            ['name' => '吉林省', 'code' => '220000', 'level' => 1, 'parent_id' => null, 'sort_order' => 7],
            ['name' => '黑龙江省', 'code' => '230000', 'level' => 1, 'parent_id' => null, 'sort_order' => 8],
            ['name' => '上海市', 'code' => '310000', 'level' => 1, 'parent_id' => null, 'sort_order' => 9],
            ['name' => '江苏省', 'code' => '320000', 'level' => 1, 'parent_id' => null, 'sort_order' => 10],
            ['name' => '浙江省', 'code' => '330000', 'level' => 1, 'parent_id' => null, 'sort_order' => 11],
            ['name' => '安徽省', 'code' => '340000', 'level' => 1, 'parent_id' => null, 'sort_order' => 12],
            ['name' => '福建省', 'code' => '350000', 'level' => 1, 'parent_id' => null, 'sort_order' => 13],
            ['name' => '江西省', 'code' => '360000', 'level' => 1, 'parent_id' => null, 'sort_order' => 14],
            ['name' => '山东省', 'code' => '370000', 'level' => 1, 'parent_id' => null, 'sort_order' => 15],
            ['name' => '河南省', 'code' => '410000', 'level' => 1, 'parent_id' => null, 'sort_order' => 16],
            ['name' => '湖北省', 'code' => '420000', 'level' => 1, 'parent_id' => null, 'sort_order' => 17],
            ['name' => '湖南省', 'code' => '430000', 'level' => 1, 'parent_id' => null, 'sort_order' => 18],
            ['name' => '广东省', 'code' => '440000', 'level' => 1, 'parent_id' => null, 'sort_order' => 19],
            ['name' => '广西壮族自治区', 'code' => '450000', 'level' => 1, 'parent_id' => null, 'sort_order' => 20],
            ['name' => '海南省', 'code' => '460000', 'level' => 1, 'parent_id' => null, 'sort_order' => 21],
            ['name' => '重庆市', 'code' => '500000', 'level' => 1, 'parent_id' => null, 'sort_order' => 22],
            ['name' => '四川省', 'code' => '510000', 'level' => 1, 'parent_id' => null, 'sort_order' => 23],
            ['name' => '贵州省', 'code' => '520000', 'level' => 1, 'parent_id' => null, 'sort_order' => 24],
            ['name' => '云南省', 'code' => '530000', 'level' => 1, 'parent_id' => null, 'sort_order' => 25],
            ['name' => '西藏自治区', 'code' => '540000', 'level' => 1, 'parent_id' => null, 'sort_order' => 26],
            ['name' => '陕西省', 'code' => '610000', 'level' => 1, 'parent_id' => null, 'sort_order' => 27],
            ['name' => '甘肃省', 'code' => '620000', 'level' => 1, 'parent_id' => null, 'sort_order' => 28],
            ['name' => '青海省', 'code' => '630000', 'level' => 1, 'parent_id' => null, 'sort_order' => 29],
            ['name' => '宁夏回族自治区', 'code' => '640000', 'level' => 1, 'parent_id' => null, 'sort_order' => 30],
            ['name' => '新疆维吾尔自治区', 'code' => '650000', 'level' => 1, 'parent_id' => null, 'sort_order' => 31],
            ['name' => '香港特别行政区', 'code' => '810000', 'level' => 1, 'parent_id' => null, 'sort_order' => 32],
            ['name' => '澳门特别行政区', 'code' => '820000', 'level' => 1, 'parent_id' => null, 'sort_order' => 33],
            ['name' => '台湾省', 'code' => '710000', 'level' => 1, 'parent_id' => null, 'sort_order' => 34],
        ];

        foreach ($regions as $region) {
            \App\Models\Region::create($region);
        }

        // 添加一些示例城市（以广东省为例）
        $guangdong = \App\Models\Region::where('code', '440000')->first();
        if ($guangdong) {
            $cities = [
                ['name' => '广州市', 'code' => '440100', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 1],
                ['name' => '深圳市', 'code' => '440300', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 2],
                ['name' => '珠海市', 'code' => '440400', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 3],
                ['name' => '汕头市', 'code' => '440500', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 4],
                ['name' => '佛山市', 'code' => '440600', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 5],
                ['name' => '韶关市', 'code' => '440200', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 6],
                ['name' => '湛江市', 'code' => '440800', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 7],
                ['name' => '肇庆市', 'code' => '441200', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 8],
                ['name' => '江门市', 'code' => '440700', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 9],
                ['name' => '茂名市', 'code' => '440900', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 10],
                ['name' => '惠州市', 'code' => '441300', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 11],
                ['name' => '梅州市', 'code' => '441400', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 12],
                ['name' => '汕尾市', 'code' => '441500', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 13],
                ['name' => '河源市', 'code' => '441600', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 14],
                ['name' => '阳江市', 'code' => '441700', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 15],
                ['name' => '清远市', 'code' => '441800', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 16],
                ['name' => '东莞市', 'code' => '441900', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 17],
                ['name' => '中山市', 'code' => '442000', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 18],
                ['name' => '潮州市', 'code' => '445100', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 19],
                ['name' => '揭阳市', 'code' => '445200', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 20],
                ['name' => '云浮市', 'code' => '445300', 'level' => 2, 'parent_id' => $guangdong->id, 'sort_order' => 21],
            ];

            foreach ($cities as $city) {
                \App\Models\Region::create($city);
            }
        }
    }
}
