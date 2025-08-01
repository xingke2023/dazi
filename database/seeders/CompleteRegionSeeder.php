<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class CompleteRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 先填充所有省份
        $provinces = $this->getProvinces();
        $provinceIds = [];
        
        foreach ($provinces as $province) {
            $provinceModel = Region::create($province);
            $provinceIds[$province['code']] = $provinceModel->id;
        }
        
        // 然后填充所有地级市
        $cities = $this->getCities();
        
        foreach ($cities as $city) {
            $city['parent_id'] = $provinceIds[$city['province_code']] ?? null;
            unset($city['province_code']); // 移除临时字段
            Region::create($city);
        }
    }
    
    private function getProvinces(): array
    {
        return [
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
            ['name' => '台湾省', 'code' => '710000', 'level' => 1, 'parent_id' => null, 'sort_order' => 32],
            ['name' => '香港特别行政区', 'code' => '810000', 'level' => 1, 'parent_id' => null, 'sort_order' => 33],
            ['name' => '澳门特别行政区', 'code' => '820000', 'level' => 1, 'parent_id' => null, 'sort_order' => 34],
        ];
    }
    
    private function getCities(): array
    {
        return [
            // 北京市
            ['name' => '北京市', 'code' => '110100', 'level' => 2, 'province_code' => '110000', 'sort_order' => 1],
            
            // 天津市
            ['name' => '天津市', 'code' => '120100', 'level' => 2, 'province_code' => '120000', 'sort_order' => 1],
            
            // 河北省
            ['name' => '石家庄市', 'code' => '130100', 'level' => 2, 'province_code' => '130000', 'sort_order' => 1],
            ['name' => '唐山市', 'code' => '130200', 'level' => 2, 'province_code' => '130000', 'sort_order' => 2],
            ['name' => '秦皇岛市', 'code' => '130300', 'level' => 2, 'province_code' => '130000', 'sort_order' => 3],
            ['name' => '邯郸市', 'code' => '130400', 'level' => 2, 'province_code' => '130000', 'sort_order' => 4],
            ['name' => '邢台市', 'code' => '130500', 'level' => 2, 'province_code' => '130000', 'sort_order' => 5],
            ['name' => '保定市', 'code' => '130600', 'level' => 2, 'province_code' => '130000', 'sort_order' => 6],
            ['name' => '张家口市', 'code' => '130700', 'level' => 2, 'province_code' => '130000', 'sort_order' => 7],
            ['name' => '承德市', 'code' => '130800', 'level' => 2, 'province_code' => '130000', 'sort_order' => 8],
            ['name' => '沧州市', 'code' => '130900', 'level' => 2, 'province_code' => '130000', 'sort_order' => 9],
            ['name' => '廊坊市', 'code' => '131000', 'level' => 2, 'province_code' => '130000', 'sort_order' => 10],
            ['name' => '衡水市', 'code' => '131100', 'level' => 2, 'province_code' => '130000', 'sort_order' => 11],
            
            // 山西省
            ['name' => '太原市', 'code' => '140100', 'level' => 2, 'province_code' => '140000', 'sort_order' => 1],
            ['name' => '大同市', 'code' => '140200', 'level' => 2, 'province_code' => '140000', 'sort_order' => 2],
            ['name' => '阳泉市', 'code' => '140300', 'level' => 2, 'province_code' => '140000', 'sort_order' => 3],
            ['name' => '长治市', 'code' => '140400', 'level' => 2, 'province_code' => '140000', 'sort_order' => 4],
            ['name' => '晋城市', 'code' => '140500', 'level' => 2, 'province_code' => '140000', 'sort_order' => 5],
            ['name' => '朔州市', 'code' => '140600', 'level' => 2, 'province_code' => '140000', 'sort_order' => 6],
            ['name' => '晋中市', 'code' => '140700', 'level' => 2, 'province_code' => '140000', 'sort_order' => 7],
            ['name' => '运城市', 'code' => '140800', 'level' => 2, 'province_code' => '140000', 'sort_order' => 8],
            ['name' => '忻州市', 'code' => '140900', 'level' => 2, 'province_code' => '140000', 'sort_order' => 9],
            ['name' => '临汾市', 'code' => '141000', 'level' => 2, 'province_code' => '140000', 'sort_order' => 10],
            ['name' => '吕梁市', 'code' => '141100', 'level' => 2, 'province_code' => '140000', 'sort_order' => 11],
            
            // 内蒙古自治区
            ['name' => '呼和浩特市', 'code' => '150100', 'level' => 2, 'province_code' => '150000', 'sort_order' => 1],
            ['name' => '包头市', 'code' => '150200', 'level' => 2, 'province_code' => '150000', 'sort_order' => 2],
            ['name' => '乌海市', 'code' => '150300', 'level' => 2, 'province_code' => '150000', 'sort_order' => 3],
            ['name' => '赤峰市', 'code' => '150400', 'level' => 2, 'province_code' => '150000', 'sort_order' => 4],
            ['name' => '通辽市', 'code' => '150500', 'level' => 2, 'province_code' => '150000', 'sort_order' => 5],
            ['name' => '鄂尔多斯市', 'code' => '150600', 'level' => 2, 'province_code' => '150000', 'sort_order' => 6],
            ['name' => '呼伦贝尔市', 'code' => '150700', 'level' => 2, 'province_code' => '150000', 'sort_order' => 7],
            ['name' => '巴彦淖尔市', 'code' => '150800', 'level' => 2, 'province_code' => '150000', 'sort_order' => 8],
            ['name' => '乌兰察布市', 'code' => '150900', 'level' => 2, 'province_code' => '150000', 'sort_order' => 9],
            ['name' => '兴安盟', 'code' => '152200', 'level' => 2, 'province_code' => '150000', 'sort_order' => 10],
            ['name' => '锡林郭勒盟', 'code' => '152500', 'level' => 2, 'province_code' => '150000', 'sort_order' => 11],
            ['name' => '阿拉善盟', 'code' => '152900', 'level' => 2, 'province_code' => '150000', 'sort_order' => 12],
            
            // 辽宁省
            ['name' => '沈阳市', 'code' => '210100', 'level' => 2, 'province_code' => '210000', 'sort_order' => 1],
            ['name' => '大连市', 'code' => '210200', 'level' => 2, 'province_code' => '210000', 'sort_order' => 2],
            ['name' => '鞍山市', 'code' => '210300', 'level' => 2, 'province_code' => '210000', 'sort_order' => 3],
            ['name' => '抚顺市', 'code' => '210400', 'level' => 2, 'province_code' => '210000', 'sort_order' => 4],
            ['name' => '本溪市', 'code' => '210500', 'level' => 2, 'province_code' => '210000', 'sort_order' => 5],
            ['name' => '丹东市', 'code' => '210600', 'level' => 2, 'province_code' => '210000', 'sort_order' => 6],
            ['name' => '锦州市', 'code' => '210700', 'level' => 2, 'province_code' => '210000', 'sort_order' => 7],
            ['name' => '营口市', 'code' => '210800', 'level' => 2, 'province_code' => '210000', 'sort_order' => 8],
            ['name' => '阜新市', 'code' => '210900', 'level' => 2, 'province_code' => '210000', 'sort_order' => 9],
            ['name' => '辽阳市', 'code' => '211000', 'level' => 2, 'province_code' => '210000', 'sort_order' => 10],
            ['name' => '盘锦市', 'code' => '211100', 'level' => 2, 'province_code' => '210000', 'sort_order' => 11],
            ['name' => '铁岭市', 'code' => '211200', 'level' => 2, 'province_code' => '210000', 'sort_order' => 12],
            ['name' => '朝阳市', 'code' => '211300', 'level' => 2, 'province_code' => '210000', 'sort_order' => 13],
            ['name' => '葫芦岛市', 'code' => '211400', 'level' => 2, 'province_code' => '210000', 'sort_order' => 14],
            
            // 吉林省
            ['name' => '长春市', 'code' => '220100', 'level' => 2, 'province_code' => '220000', 'sort_order' => 1],
            ['name' => '吉林市', 'code' => '220200', 'level' => 2, 'province_code' => '220000', 'sort_order' => 2],
            ['name' => '四平市', 'code' => '220300', 'level' => 2, 'province_code' => '220000', 'sort_order' => 3],
            ['name' => '辽源市', 'code' => '220400', 'level' => 2, 'province_code' => '220000', 'sort_order' => 4],
            ['name' => '通化市', 'code' => '220500', 'level' => 2, 'province_code' => '220000', 'sort_order' => 5],
            ['name' => '白山市', 'code' => '220600', 'level' => 2, 'province_code' => '220000', 'sort_order' => 6],
            ['name' => '松原市', 'code' => '220700', 'level' => 2, 'province_code' => '220000', 'sort_order' => 7],
            ['name' => '白城市', 'code' => '220800', 'level' => 2, 'province_code' => '220000', 'sort_order' => 8],
            ['name' => '延边朝鲜族自治州', 'code' => '222400', 'level' => 2, 'province_code' => '220000', 'sort_order' => 9],
            
            // 黑龙江省
            ['name' => '哈尔滨市', 'code' => '230100', 'level' => 2, 'province_code' => '230000', 'sort_order' => 1],
            ['name' => '齐齐哈尔市', 'code' => '230200', 'level' => 2, 'province_code' => '230000', 'sort_order' => 2],
            ['name' => '鸡西市', 'code' => '230300', 'level' => 2, 'province_code' => '230000', 'sort_order' => 3],
            ['name' => '鹤岗市', 'code' => '230400', 'level' => 2, 'province_code' => '230000', 'sort_order' => 4],
            ['name' => '双鸭山市', 'code' => '230500', 'level' => 2, 'province_code' => '230000', 'sort_order' => 5],
            ['name' => '大庆市', 'code' => '230600', 'level' => 2, 'province_code' => '230000', 'sort_order' => 6],
            ['name' => '伊春市', 'code' => '230700', 'level' => 2, 'province_code' => '230000', 'sort_order' => 7],
            ['name' => '佳木斯市', 'code' => '230800', 'level' => 2, 'province_code' => '230000', 'sort_order' => 8],
            ['name' => '七台河市', 'code' => '230900', 'level' => 2, 'province_code' => '230000', 'sort_order' => 9],
            ['name' => '牡丹江市', 'code' => '231000', 'level' => 2, 'province_code' => '230000', 'sort_order' => 10],
            ['name' => '黑河市', 'code' => '231100', 'level' => 2, 'province_code' => '230000', 'sort_order' => 11],
            ['name' => '绥化市', 'code' => '231200', 'level' => 2, 'province_code' => '230000', 'sort_order' => 12],
            ['name' => '大兴安岭地区', 'code' => '232700', 'level' => 2, 'province_code' => '230000', 'sort_order' => 13],
            
            // 上海市
            ['name' => '上海市', 'code' => '310100', 'level' => 2, 'province_code' => '310000', 'sort_order' => 1],
            
            // 江苏省
            ['name' => '南京市', 'code' => '320100', 'level' => 2, 'province_code' => '320000', 'sort_order' => 1],
            ['name' => '无锡市', 'code' => '320200', 'level' => 2, 'province_code' => '320000', 'sort_order' => 2],
            ['name' => '徐州市', 'code' => '320300', 'level' => 2, 'province_code' => '320000', 'sort_order' => 3],
            ['name' => '常州市', 'code' => '320400', 'level' => 2, 'province_code' => '320000', 'sort_order' => 4],
            ['name' => '苏州市', 'code' => '320500', 'level' => 2, 'province_code' => '320000', 'sort_order' => 5],
            ['name' => '南通市', 'code' => '320600', 'level' => 2, 'province_code' => '320000', 'sort_order' => 6],
            ['name' => '连云港市', 'code' => '320700', 'level' => 2, 'province_code' => '320000', 'sort_order' => 7],
            ['name' => '淮安市', 'code' => '320800', 'level' => 2, 'province_code' => '320000', 'sort_order' => 8],
            ['name' => '盐城市', 'code' => '320900', 'level' => 2, 'province_code' => '320000', 'sort_order' => 9],
            ['name' => '扬州市', 'code' => '321000', 'level' => 2, 'province_code' => '320000', 'sort_order' => 10],
            ['name' => '镇江市', 'code' => '321100', 'level' => 2, 'province_code' => '320000', 'sort_order' => 11],
            ['name' => '泰州市', 'code' => '321200', 'level' => 2, 'province_code' => '320000', 'sort_order' => 12],
            ['name' => '宿迁市', 'code' => '321300', 'level' => 2, 'province_code' => '320000', 'sort_order' => 13],
            
            // 浙江省
            ['name' => '杭州市', 'code' => '330100', 'level' => 2, 'province_code' => '330000', 'sort_order' => 1],
            ['name' => '宁波市', 'code' => '330200', 'level' => 2, 'province_code' => '330000', 'sort_order' => 2],
            ['name' => '温州市', 'code' => '330300', 'level' => 2, 'province_code' => '330000', 'sort_order' => 3],
            ['name' => '嘉兴市', 'code' => '330400', 'level' => 2, 'province_code' => '330000', 'sort_order' => 4],
            ['name' => '湖州市', 'code' => '330500', 'level' => 2, 'province_code' => '330000', 'sort_order' => 5],
            ['name' => '绍兴市', 'code' => '330600', 'level' => 2, 'province_code' => '330000', 'sort_order' => 6],
            ['name' => '金华市', 'code' => '330700', 'level' => 2, 'province_code' => '330000', 'sort_order' => 7],
            ['name' => '衢州市', 'code' => '330800', 'level' => 2, 'province_code' => '330000', 'sort_order' => 8],
            ['name' => '舟山市', 'code' => '330900', 'level' => 2, 'province_code' => '330000', 'sort_order' => 9],
            ['name' => '台州市', 'code' => '331000', 'level' => 2, 'province_code' => '330000', 'sort_order' => 10],
            ['name' => '丽水市', 'code' => '331100', 'level' => 2, 'province_code' => '330000', 'sort_order' => 11],
            
            // 安徽省
            ['name' => '合肥市', 'code' => '340100', 'level' => 2, 'province_code' => '340000', 'sort_order' => 1],
            ['name' => '芜湖市', 'code' => '340200', 'level' => 2, 'province_code' => '340000', 'sort_order' => 2],
            ['name' => '蚌埠市', 'code' => '340300', 'level' => 2, 'province_code' => '340000', 'sort_order' => 3],
            ['name' => '淮南市', 'code' => '340400', 'level' => 2, 'province_code' => '340000', 'sort_order' => 4],
            ['name' => '马鞍山市', 'code' => '340500', 'level' => 2, 'province_code' => '340000', 'sort_order' => 5],
            ['name' => '淮北市', 'code' => '340600', 'level' => 2, 'province_code' => '340000', 'sort_order' => 6],
            ['name' => '铜陵市', 'code' => '340700', 'level' => 2, 'province_code' => '340000', 'sort_order' => 7],
            ['name' => '安庆市', 'code' => '340800', 'level' => 2, 'province_code' => '340000', 'sort_order' => 8],
            ['name' => '黄山市', 'code' => '341000', 'level' => 2, 'province_code' => '340000', 'sort_order' => 9],
            ['name' => '滁州市', 'code' => '341100', 'level' => 2, 'province_code' => '340000', 'sort_order' => 10],
            ['name' => '阜阳市', 'code' => '341200', 'level' => 2, 'province_code' => '340000', 'sort_order' => 11],
            ['name' => '宿州市', 'code' => '341300', 'level' => 2, 'province_code' => '340000', 'sort_order' => 12],
            ['name' => '六安市', 'code' => '341500', 'level' => 2, 'province_code' => '340000', 'sort_order' => 13],
            ['name' => '亳州市', 'code' => '341600', 'level' => 2, 'province_code' => '340000', 'sort_order' => 14],
            ['name' => '池州市', 'code' => '341700', 'level' => 2, 'province_code' => '340000', 'sort_order' => 15],
            ['name' => '宣城市', 'code' => '341800', 'level' => 2, 'province_code' => '340000', 'sort_order' => 16],
            
            // 福建省
            ['name' => '福州市', 'code' => '350100', 'level' => 2, 'province_code' => '350000', 'sort_order' => 1],
            ['name' => '厦门市', 'code' => '350200', 'level' => 2, 'province_code' => '350000', 'sort_order' => 2],
            ['name' => '莆田市', 'code' => '350300', 'level' => 2, 'province_code' => '350000', 'sort_order' => 3],
            ['name' => '三明市', 'code' => '350400', 'level' => 2, 'province_code' => '350000', 'sort_order' => 4],
            ['name' => '泉州市', 'code' => '350500', 'level' => 2, 'province_code' => '350000', 'sort_order' => 5],
            ['name' => '漳州市', 'code' => '350600', 'level' => 2, 'province_code' => '350000', 'sort_order' => 6],
            ['name' => '南平市', 'code' => '350700', 'level' => 2, 'province_code' => '350000', 'sort_order' => 7],
            ['name' => '龙岩市', 'code' => '350800', 'level' => 2, 'province_code' => '350000', 'sort_order' => 8],
            ['name' => '宁德市', 'code' => '350900', 'level' => 2, 'province_code' => '350000', 'sort_order' => 9],
            
            // 江西省
            ['name' => '南昌市', 'code' => '360100', 'level' => 2, 'province_code' => '360000', 'sort_order' => 1],
            ['name' => '景德镇市', 'code' => '360200', 'level' => 2, 'province_code' => '360000', 'sort_order' => 2],
            ['name' => '萍乡市', 'code' => '360300', 'level' => 2, 'province_code' => '360000', 'sort_order' => 3],
            ['name' => '九江市', 'code' => '360400', 'level' => 2, 'province_code' => '360000', 'sort_order' => 4],
            ['name' => '新余市', 'code' => '360500', 'level' => 2, 'province_code' => '360000', 'sort_order' => 5],
            ['name' => '鹰潭市', 'code' => '360600', 'level' => 2, 'province_code' => '360000', 'sort_order' => 6],
            ['name' => '赣州市', 'code' => '360700', 'level' => 2, 'province_code' => '360000', 'sort_order' => 7],
            ['name' => '吉安市', 'code' => '360800', 'level' => 2, 'province_code' => '360000', 'sort_order' => 8],
            ['name' => '宜春市', 'code' => '360900', 'level' => 2, 'province_code' => '360000', 'sort_order' => 9],
            ['name' => '抚州市', 'code' => '361000', 'level' => 2, 'province_code' => '360000', 'sort_order' => 10],
            ['name' => '上饶市', 'code' => '361100', 'level' => 2, 'province_code' => '360000', 'sort_order' => 11],
            
            // 山东省
            ['name' => '济南市', 'code' => '370100', 'level' => 2, 'province_code' => '370000', 'sort_order' => 1],
            ['name' => '青岛市', 'code' => '370200', 'level' => 2, 'province_code' => '370000', 'sort_order' => 2],
            ['name' => '淄博市', 'code' => '370300', 'level' => 2, 'province_code' => '370000', 'sort_order' => 3],
            ['name' => '枣庄市', 'code' => '370400', 'level' => 2, 'province_code' => '370000', 'sort_order' => 4],
            ['name' => '东营市', 'code' => '370500', 'level' => 2, 'province_code' => '370000', 'sort_order' => 5],
            ['name' => '烟台市', 'code' => '370600', 'level' => 2, 'province_code' => '370000', 'sort_order' => 6],
            ['name' => '潍坊市', 'code' => '370700', 'level' => 2, 'province_code' => '370000', 'sort_order' => 7],
            ['name' => '济宁市', 'code' => '370800', 'level' => 2, 'province_code' => '370000', 'sort_order' => 8],
            ['name' => '泰安市', 'code' => '370900', 'level' => 2, 'province_code' => '370000', 'sort_order' => 9],
            ['name' => '威海市', 'code' => '371000', 'level' => 2, 'province_code' => '370000', 'sort_order' => 10],
            ['name' => '日照市', 'code' => '371100', 'level' => 2, 'province_code' => '370000', 'sort_order' => 11],
            ['name' => '临沂市', 'code' => '371300', 'level' => 2, 'province_code' => '370000', 'sort_order' => 12],
            ['name' => '德州市', 'code' => '371400', 'level' => 2, 'province_code' => '370000', 'sort_order' => 13],
            ['name' => '聊城市', 'code' => '371500', 'level' => 2, 'province_code' => '370000', 'sort_order' => 14],
            ['name' => '滨州市', 'code' => '371600', 'level' => 2, 'province_code' => '370000', 'sort_order' => 15],
            ['name' => '菏泽市', 'code' => '371700', 'level' => 2, 'province_code' => '370000', 'sort_order' => 16],
            
            // 河南省
            ['name' => '郑州市', 'code' => '410100', 'level' => 2, 'province_code' => '410000', 'sort_order' => 1],
            ['name' => '开封市', 'code' => '410200', 'level' => 2, 'province_code' => '410000', 'sort_order' => 2],
            ['name' => '洛阳市', 'code' => '410300', 'level' => 2, 'province_code' => '410000', 'sort_order' => 3],
            ['name' => '平顶山市', 'code' => '410400', 'level' => 2, 'province_code' => '410000', 'sort_order' => 4],
            ['name' => '安阳市', 'code' => '410500', 'level' => 2, 'province_code' => '410000', 'sort_order' => 5],
            ['name' => '鹤壁市', 'code' => '410600', 'level' => 2, 'province_code' => '410000', 'sort_order' => 6],
            ['name' => '新乡市', 'code' => '410700', 'level' => 2, 'province_code' => '410000', 'sort_order' => 7],
            ['name' => '焦作市', 'code' => '410800', 'level' => 2, 'province_code' => '410000', 'sort_order' => 8],
            ['name' => '濮阳市', 'code' => '410900', 'level' => 2, 'province_code' => '410000', 'sort_order' => 9],
            ['name' => '许昌市', 'code' => '411000', 'level' => 2, 'province_code' => '410000', 'sort_order' => 10],
            ['name' => '漯河市', 'code' => '411100', 'level' => 2, 'province_code' => '410000', 'sort_order' => 11],
            ['name' => '三门峡市', 'code' => '411200', 'level' => 2, 'province_code' => '410000', 'sort_order' => 12],
            ['name' => '南阳市', 'code' => '411300', 'level' => 2, 'province_code' => '410000', 'sort_order' => 13],
            ['name' => '商丘市', 'code' => '411400', 'level' => 2, 'province_code' => '410000', 'sort_order' => 14],
            ['name' => '信阳市', 'code' => '411500', 'level' => 2, 'province_code' => '410000', 'sort_order' => 15],
            ['name' => '周口市', 'code' => '411600', 'level' => 2, 'province_code' => '410000', 'sort_order' => 16],
            ['name' => '驻马店市', 'code' => '411700', 'level' => 2, 'province_code' => '410000', 'sort_order' => 17],
            ['name' => '济源市', 'code' => '419001', 'level' => 2, 'province_code' => '410000', 'sort_order' => 18],
            
            // 湖北省
            ['name' => '武汉市', 'code' => '420100', 'level' => 2, 'province_code' => '420000', 'sort_order' => 1],
            ['name' => '黄石市', 'code' => '420200', 'level' => 2, 'province_code' => '420000', 'sort_order' => 2],
            ['name' => '十堰市', 'code' => '420300', 'level' => 2, 'province_code' => '420000', 'sort_order' => 3],
            ['name' => '宜昌市', 'code' => '420500', 'level' => 2, 'province_code' => '420000', 'sort_order' => 4],
            ['name' => '襄阳市', 'code' => '420600', 'level' => 2, 'province_code' => '420000', 'sort_order' => 5],
            ['name' => '鄂州市', 'code' => '420700', 'level' => 2, 'province_code' => '420000', 'sort_order' => 6],
            ['name' => '荆门市', 'code' => '420800', 'level' => 2, 'province_code' => '420000', 'sort_order' => 7],
            ['name' => '孝感市', 'code' => '420900', 'level' => 2, 'province_code' => '420000', 'sort_order' => 8],
            ['name' => '荆州市', 'code' => '421000', 'level' => 2, 'province_code' => '420000', 'sort_order' => 9],
            ['name' => '黄冈市', 'code' => '421100', 'level' => 2, 'province_code' => '420000', 'sort_order' => 10],
            ['name' => '咸宁市', 'code' => '421200', 'level' => 2, 'province_code' => '420000', 'sort_order' => 11],
            ['name' => '随州市', 'code' => '421300', 'level' => 2, 'province_code' => '420000', 'sort_order' => 12],
            ['name' => '恩施土家族苗族自治州', 'code' => '422800', 'level' => 2, 'province_code' => '420000', 'sort_order' => 13],
            ['name' => '仙桃市', 'code' => '429004', 'level' => 2, 'province_code' => '420000', 'sort_order' => 14],
            ['name' => '潜江市', 'code' => '429005', 'level' => 2, 'province_code' => '420000', 'sort_order' => 15],
            ['name' => '天门市', 'code' => '429006', 'level' => 2, 'province_code' => '420000', 'sort_order' => 16],
            ['name' => '神农架林区', 'code' => '429021', 'level' => 2, 'province_code' => '420000', 'sort_order' => 17],
            
            // 湖南省
            ['name' => '长沙市', 'code' => '430100', 'level' => 2, 'province_code' => '430000', 'sort_order' => 1],
            ['name' => '株洲市', 'code' => '430200', 'level' => 2, 'province_code' => '430000', 'sort_order' => 2],
            ['name' => '湘潭市', 'code' => '430300', 'level' => 2, 'province_code' => '430000', 'sort_order' => 3],
            ['name' => '衡阳市', 'code' => '430400', 'level' => 2, 'province_code' => '430000', 'sort_order' => 4],
            ['name' => '邵阳市', 'code' => '430500', 'level' => 2, 'province_code' => '430000', 'sort_order' => 5],
            ['name' => '岳阳市', 'code' => '430600', 'level' => 2, 'province_code' => '430000', 'sort_order' => 6],
            ['name' => '常德市', 'code' => '430700', 'level' => 2, 'province_code' => '430000', 'sort_order' => 7],
            ['name' => '张家界市', 'code' => '430800', 'level' => 2, 'province_code' => '430000', 'sort_order' => 8],
            ['name' => '益阳市', 'code' => '430900', 'level' => 2, 'province_code' => '430000', 'sort_order' => 9],
            ['name' => '郴州市', 'code' => '431000', 'level' => 2, 'province_code' => '430000', 'sort_order' => 10],
            ['name' => '永州市', 'code' => '431100', 'level' => 2, 'province_code' => '430000', 'sort_order' => 11],
            ['name' => '怀化市', 'code' => '431200', 'level' => 2, 'province_code' => '430000', 'sort_order' => 12],
            ['name' => '娄底市', 'code' => '431300', 'level' => 2, 'province_code' => '430000', 'sort_order' => 13],
            ['name' => '湘西土家族苗族自治州', 'code' => '433100', 'level' => 2, 'province_code' => '430000', 'sort_order' => 14],
            
            // 广东省
            ['name' => '广州市', 'code' => '440100', 'level' => 2, 'province_code' => '440000', 'sort_order' => 1],
            ['name' => '韶关市', 'code' => '440200', 'level' => 2, 'province_code' => '440000', 'sort_order' => 2],
            ['name' => '深圳市', 'code' => '440300', 'level' => 2, 'province_code' => '440000', 'sort_order' => 3],
            ['name' => '珠海市', 'code' => '440400', 'level' => 2, 'province_code' => '440000', 'sort_order' => 4],
            ['name' => '汕头市', 'code' => '440500', 'level' => 2, 'province_code' => '440000', 'sort_order' => 5],
            ['name' => '佛山市', 'code' => '440600', 'level' => 2, 'province_code' => '440000', 'sort_order' => 6],
            ['name' => '江门市', 'code' => '440700', 'level' => 2, 'province_code' => '440000', 'sort_order' => 7],
            ['name' => '湛江市', 'code' => '440800', 'level' => 2, 'province_code' => '440000', 'sort_order' => 8],
            ['name' => '茂名市', 'code' => '440900', 'level' => 2, 'province_code' => '440000', 'sort_order' => 9],
            ['name' => '肇庆市', 'code' => '441200', 'level' => 2, 'province_code' => '440000', 'sort_order' => 10],
            ['name' => '惠州市', 'code' => '441300', 'level' => 2, 'province_code' => '440000', 'sort_order' => 11],
            ['name' => '梅州市', 'code' => '441400', 'level' => 2, 'province_code' => '440000', 'sort_order' => 12],
            ['name' => '汕尾市', 'code' => '441500', 'level' => 2, 'province_code' => '440000', 'sort_order' => 13],
            ['name' => '河源市', 'code' => '441600', 'level' => 2, 'province_code' => '440000', 'sort_order' => 14],
            ['name' => '阳江市', 'code' => '441700', 'level' => 2, 'province_code' => '440000', 'sort_order' => 15],
            ['name' => '清远市', 'code' => '441800', 'level' => 2, 'province_code' => '440000', 'sort_order' => 16],
            ['name' => '东莞市', 'code' => '441900', 'level' => 2, 'province_code' => '440000', 'sort_order' => 17],
            ['name' => '中山市', 'code' => '442000', 'level' => 2, 'province_code' => '440000', 'sort_order' => 18],
            ['name' => '潮州市', 'code' => '445100', 'level' => 2, 'province_code' => '440000', 'sort_order' => 19],
            ['name' => '揭阳市', 'code' => '445200', 'level' => 2, 'province_code' => '440000', 'sort_order' => 20],
            ['name' => '云浮市', 'code' => '445300', 'level' => 2, 'province_code' => '440000', 'sort_order' => 21],
            
            // 广西壮族自治区
            ['name' => '南宁市', 'code' => '450100', 'level' => 2, 'province_code' => '450000', 'sort_order' => 1],
            ['name' => '柳州市', 'code' => '450200', 'level' => 2, 'province_code' => '450000', 'sort_order' => 2],
            ['name' => '桂林市', 'code' => '450300', 'level' => 2, 'province_code' => '450000', 'sort_order' => 3],
            ['name' => '梧州市', 'code' => '450400', 'level' => 2, 'province_code' => '450000', 'sort_order' => 4],
            ['name' => '北海市', 'code' => '450500', 'level' => 2, 'province_code' => '450000', 'sort_order' => 5],
            ['name' => '防城港市', 'code' => '450600', 'level' => 2, 'province_code' => '450000', 'sort_order' => 6],
            ['name' => '钦州市', 'code' => '450700', 'level' => 2, 'province_code' => '450000', 'sort_order' => 7],
            ['name' => '贵港市', 'code' => '450800', 'level' => 2, 'province_code' => '450000', 'sort_order' => 8],
            ['name' => '玉林市', 'code' => '450900', 'level' => 2, 'province_code' => '450000', 'sort_order' => 9],
            ['name' => '百色市', 'code' => '451000', 'level' => 2, 'province_code' => '450000', 'sort_order' => 10],
            ['name' => '贺州市', 'code' => '451100', 'level' => 2, 'province_code' => '450000', 'sort_order' => 11],
            ['name' => '河池市', 'code' => '451200', 'level' => 2, 'province_code' => '450000', 'sort_order' => 12],
            ['name' => '来宾市', 'code' => '451300', 'level' => 2, 'province_code' => '450000', 'sort_order' => 13],
            ['name' => '崇左市', 'code' => '451400', 'level' => 2, 'province_code' => '450000', 'sort_order' => 14],
            
            // 海南省
            ['name' => '海口市', 'code' => '460100', 'level' => 2, 'province_code' => '460000', 'sort_order' => 1],
            ['name' => '三亚市', 'code' => '460200', 'level' => 2, 'province_code' => '460000', 'sort_order' => 2],
            ['name' => '三沙市', 'code' => '460300', 'level' => 2, 'province_code' => '460000', 'sort_order' => 3],
            ['name' => '儋州市', 'code' => '460400', 'level' => 2, 'province_code' => '460000', 'sort_order' => 4],
            ['name' => '五指山市', 'code' => '469001', 'level' => 2, 'province_code' => '460000', 'sort_order' => 5],
            ['name' => '琼海市', 'code' => '469002', 'level' => 2, 'province_code' => '460000', 'sort_order' => 6],
            ['name' => '文昌市', 'code' => '469005', 'level' => 2, 'province_code' => '460000', 'sort_order' => 7],
            ['name' => '万宁市', 'code' => '469006', 'level' => 2, 'province_code' => '460000', 'sort_order' => 8],
            ['name' => '东方市', 'code' => '469007', 'level' => 2, 'province_code' => '460000', 'sort_order' => 9],
            ['name' => '定安县', 'code' => '469021', 'level' => 2, 'province_code' => '460000', 'sort_order' => 10],
            ['name' => '屯昌县', 'code' => '469022', 'level' => 2, 'province_code' => '460000', 'sort_order' => 11],
            ['name' => '澄迈县', 'code' => '469023', 'level' => 2, 'province_code' => '460000', 'sort_order' => 12],
            ['name' => '临高县', 'code' => '469024', 'level' => 2, 'province_code' => '460000', 'sort_order' => 13],
            ['name' => '白沙黎族自治县', 'code' => '469025', 'level' => 2, 'province_code' => '460000', 'sort_order' => 14],
            ['name' => '昌江黎族自治县', 'code' => '469026', 'level' => 2, 'province_code' => '460000', 'sort_order' => 15],
            ['name' => '乐东黎族自治县', 'code' => '469027', 'level' => 2, 'province_code' => '460000', 'sort_order' => 16],
            ['name' => '陵水黎族自治县', 'code' => '469028', 'level' => 2, 'province_code' => '460000', 'sort_order' => 17],
            ['name' => '保亭黎族苗族自治县', 'code' => '469029', 'level' => 2, 'province_code' => '460000', 'sort_order' => 18],
            ['name' => '琼中黎族苗族自治县', 'code' => '469030', 'level' => 2, 'province_code' => '460000', 'sort_order' => 19],
            
            // 重庆市
            ['name' => '重庆市', 'code' => '500100', 'level' => 2, 'province_code' => '500000', 'sort_order' => 1],
            
            // 四川省
            ['name' => '成都市', 'code' => '510100', 'level' => 2, 'province_code' => '510000', 'sort_order' => 1],
            ['name' => '自贡市', 'code' => '510300', 'level' => 2, 'province_code' => '510000', 'sort_order' => 2],
            ['name' => '攀枝花市', 'code' => '510400', 'level' => 2, 'province_code' => '510000', 'sort_order' => 3],
            ['name' => '泸州市', 'code' => '510500', 'level' => 2, 'province_code' => '510000', 'sort_order' => 4],
            ['name' => '德阳市', 'code' => '510600', 'level' => 2, 'province_code' => '510000', 'sort_order' => 5],
            ['name' => '绵阳市', 'code' => '510700', 'level' => 2, 'province_code' => '510000', 'sort_order' => 6],
            ['name' => '广元市', 'code' => '510800', 'level' => 2, 'province_code' => '510000', 'sort_order' => 7],
            ['name' => '遂宁市', 'code' => '510900', 'level' => 2, 'province_code' => '510000', 'sort_order' => 8],
            ['name' => '内江市', 'code' => '511000', 'level' => 2, 'province_code' => '510000', 'sort_order' => 9],
            ['name' => '乐山市', 'code' => '511100', 'level' => 2, 'province_code' => '510000', 'sort_order' => 10],
            ['name' => '南充市', 'code' => '511300', 'level' => 2, 'province_code' => '510000', 'sort_order' => 11],
            ['name' => '眉山市', 'code' => '511400', 'level' => 2, 'province_code' => '510000', 'sort_order' => 12],
            ['name' => '宜宾市', 'code' => '511500', 'level' => 2, 'province_code' => '510000', 'sort_order' => 13],
            ['name' => '广安市', 'code' => '511600', 'level' => 2, 'province_code' => '510000', 'sort_order' => 14],
            ['name' => '达州市', 'code' => '511700', 'level' => 2, 'province_code' => '510000', 'sort_order' => 15],
            ['name' => '雅安市', 'code' => '511800', 'level' => 2, 'province_code' => '510000', 'sort_order' => 16],
            ['name' => '巴中市', 'code' => '511900', 'level' => 2, 'province_code' => '510000', 'sort_order' => 17],
            ['name' => '资阳市', 'code' => '512000', 'level' => 2, 'province_code' => '510000', 'sort_order' => 18],
            ['name' => '阿坝藏族羌族自治州', 'code' => '513200', 'level' => 2, 'province_code' => '510000', 'sort_order' => 19],
            ['name' => '甘孜藏族自治州', 'code' => '513300', 'level' => 2, 'province_code' => '510000', 'sort_order' => 20],
            ['name' => '凉山彝族自治州', 'code' => '513400', 'level' => 2, 'province_code' => '510000', 'sort_order' => 21],
            
            // 贵州省
            ['name' => '贵阳市', 'code' => '520100', 'level' => 2, 'province_code' => '520000', 'sort_order' => 1],
            ['name' => '六盘水市', 'code' => '520200', 'level' => 2, 'province_code' => '520000', 'sort_order' => 2],
            ['name' => '遵义市', 'code' => '520300', 'level' => 2, 'province_code' => '520000', 'sort_order' => 3],
            ['name' => '安顺市', 'code' => '520400', 'level' => 2, 'province_code' => '520000', 'sort_order' => 4],
            ['name' => '毕节市', 'code' => '520500', 'level' => 2, 'province_code' => '520000', 'sort_order' => 5],
            ['name' => '铜仁市', 'code' => '520600', 'level' => 2, 'province_code' => '520000', 'sort_order' => 6],
            ['name' => '黔西南布依族苗族自治州', 'code' => '522300', 'level' => 2, 'province_code' => '520000', 'sort_order' => 7],
            ['name' => '黔东南苗族侗族自治州', 'code' => '522600', 'level' => 2, 'province_code' => '520000', 'sort_order' => 8],
            ['name' => '黔南布依族苗族自治州', 'code' => '522700', 'level' => 2, 'province_code' => '520000', 'sort_order' => 9],
            
            // 云南省
            ['name' => '昆明市', 'code' => '530100', 'level' => 2, 'province_code' => '530000', 'sort_order' => 1],
            ['name' => '曲靖市', 'code' => '530300', 'level' => 2, 'province_code' => '530000', 'sort_order' => 2],
            ['name' => '玉溪市', 'code' => '530400', 'level' => 2, 'province_code' => '530000', 'sort_order' => 3],
            ['name' => '保山市', 'code' => '530500', 'level' => 2, 'province_code' => '530000', 'sort_order' => 4],
            ['name' => '昭通市', 'code' => '530600', 'level' => 2, 'province_code' => '530000', 'sort_order' => 5],
            ['name' => '丽江市', 'code' => '530700', 'level' => 2, 'province_code' => '530000', 'sort_order' => 6],
            ['name' => '普洱市', 'code' => '530800', 'level' => 2, 'province_code' => '530000', 'sort_order' => 7],
            ['name' => '临沧市', 'code' => '530900', 'level' => 2, 'province_code' => '530000', 'sort_order' => 8],
            ['name' => '楚雄彝族自治州', 'code' => '532300', 'level' => 2, 'province_code' => '530000', 'sort_order' => 9],
            ['name' => '红河哈尼族彝族自治州', 'code' => '532500', 'level' => 2, 'province_code' => '530000', 'sort_order' => 10],
            ['name' => '文山壮族苗族自治州', 'code' => '532600', 'level' => 2, 'province_code' => '530000', 'sort_order' => 11],
            ['name' => '西双版纳傣族自治州', 'code' => '532800', 'level' => 2, 'province_code' => '530000', 'sort_order' => 12],
            ['name' => '大理白族自治州', 'code' => '532900', 'level' => 2, 'province_code' => '530000', 'sort_order' => 13],
            ['name' => '德宏傣族景颇族自治州', 'code' => '533100', 'level' => 2, 'province_code' => '530000', 'sort_order' => 14],
            ['name' => '怒江傈僳族自治州', 'code' => '533300', 'level' => 2, 'province_code' => '530000', 'sort_order' => 15],
            ['name' => '迪庆藏族自治州', 'code' => '533400', 'level' => 2, 'province_code' => '530000', 'sort_order' => 16],
            
            // 西藏自治区
            ['name' => '拉萨市', 'code' => '540100', 'level' => 2, 'province_code' => '540000', 'sort_order' => 1],
            ['name' => '日喀则市', 'code' => '540200', 'level' => 2, 'province_code' => '540000', 'sort_order' => 2],
            ['name' => '昌都市', 'code' => '540300', 'level' => 2, 'province_code' => '540000', 'sort_order' => 3],
            ['name' => '林芝市', 'code' => '540400', 'level' => 2, 'province_code' => '540000', 'sort_order' => 4],
            ['name' => '山南市', 'code' => '540500', 'level' => 2, 'province_code' => '540000', 'sort_order' => 5],
            ['name' => '那曲市', 'code' => '540600', 'level' => 2, 'province_code' => '540000', 'sort_order' => 6],
            ['name' => '阿里地区', 'code' => '542500', 'level' => 2, 'province_code' => '540000', 'sort_order' => 7],
            
            // 陕西省
            ['name' => '西安市', 'code' => '610100', 'level' => 2, 'province_code' => '610000', 'sort_order' => 1],
            ['name' => '铜川市', 'code' => '610200', 'level' => 2, 'province_code' => '610000', 'sort_order' => 2],
            ['name' => '宝鸡市', 'code' => '610300', 'level' => 2, 'province_code' => '610000', 'sort_order' => 3],
            ['name' => '咸阳市', 'code' => '610400', 'level' => 2, 'province_code' => '610000', 'sort_order' => 4],
            ['name' => '渭南市', 'code' => '610500', 'level' => 2, 'province_code' => '610000', 'sort_order' => 5],
            ['name' => '延安市', 'code' => '610600', 'level' => 2, 'province_code' => '610000', 'sort_order' => 6],
            ['name' => '汉中市', 'code' => '610700', 'level' => 2, 'province_code' => '610000', 'sort_order' => 7],
            ['name' => '榆林市', 'code' => '610800', 'level' => 2, 'province_code' => '610000', 'sort_order' => 8],
            ['name' => '安康市', 'code' => '610900', 'level' => 2, 'province_code' => '610000', 'sort_order' => 9],
            ['name' => '商洛市', 'code' => '611000', 'level' => 2, 'province_code' => '610000', 'sort_order' => 10],
            
            // 甘肃省
            ['name' => '兰州市', 'code' => '620100', 'level' => 2, 'province_code' => '620000', 'sort_order' => 1],
            ['name' => '嘉峪关市', 'code' => '620200', 'level' => 2, 'province_code' => '620000', 'sort_order' => 2],
            ['name' => '金昌市', 'code' => '620300', 'level' => 2, 'province_code' => '620000', 'sort_order' => 3],
            ['name' => '白银市', 'code' => '620400', 'level' => 2, 'province_code' => '620000', 'sort_order' => 4],
            ['name' => '天水市', 'code' => '620500', 'level' => 2, 'province_code' => '620000', 'sort_order' => 5],
            ['name' => '武威市', 'code' => '620600', 'level' => 2, 'province_code' => '620000', 'sort_order' => 6],
            ['name' => '张掖市', 'code' => '620700', 'level' => 2, 'province_code' => '620000', 'sort_order' => 7],
            ['name' => '平凉市', 'code' => '620800', 'level' => 2, 'province_code' => '620000', 'sort_order' => 8],
            ['name' => '酒泉市', 'code' => '620900', 'level' => 2, 'province_code' => '620000', 'sort_order' => 9],
            ['name' => '庆阳市', 'code' => '621000', 'level' => 2, 'province_code' => '620000', 'sort_order' => 10],
            ['name' => '定西市', 'code' => '621100', 'level' => 2, 'province_code' => '620000', 'sort_order' => 11],
            ['name' => '陇南市', 'code' => '621200', 'level' => 2, 'province_code' => '620000', 'sort_order' => 12],
            ['name' => '临夏回族自治州', 'code' => '622900', 'level' => 2, 'province_code' => '620000', 'sort_order' => 13],
            ['name' => '甘南藏族自治州', 'code' => '623000', 'level' => 2, 'province_code' => '620000', 'sort_order' => 14],
            
            // 青海省
            ['name' => '西宁市', 'code' => '630100', 'level' => 2, 'province_code' => '630000', 'sort_order' => 1],
            ['name' => '海东市', 'code' => '630200', 'level' => 2, 'province_code' => '630000', 'sort_order' => 2],
            ['name' => '海北藏族自治州', 'code' => '632200', 'level' => 2, 'province_code' => '630000', 'sort_order' => 3],
            ['name' => '黄南藏族自治州', 'code' => '632300', 'level' => 2, 'province_code' => '630000', 'sort_order' => 4],
            ['name' => '海南藏族自治州', 'code' => '632500', 'level' => 2, 'province_code' => '630000', 'sort_order' => 5],
            ['name' => '果洛藏族自治州', 'code' => '632600', 'level' => 2, 'province_code' => '630000', 'sort_order' => 6],
            ['name' => '玉树藏族自治州', 'code' => '632700', 'level' => 2, 'province_code' => '630000', 'sort_order' => 7],
            ['name' => '海西蒙古族藏族自治州', 'code' => '632800', 'level' => 2, 'province_code' => '630000', 'sort_order' => 8],
            
            // 宁夏回族自治区
            ['name' => '银川市', 'code' => '640100', 'level' => 2, 'province_code' => '640000', 'sort_order' => 1],
            ['name' => '石嘴山市', 'code' => '640200', 'level' => 2, 'province_code' => '640000', 'sort_order' => 2],
            ['name' => '吴忠市', 'code' => '640300', 'level' => 2, 'province_code' => '640000', 'sort_order' => 3],
            ['name' => '固原市', 'code' => '640400', 'level' => 2, 'province_code' => '640000', 'sort_order' => 4],
            ['name' => '中卫市', 'code' => '640500', 'level' => 2, 'province_code' => '640000', 'sort_order' => 5],
            
            // 新疆维吾尔自治区
            ['name' => '乌鲁木齐市', 'code' => '650100', 'level' => 2, 'province_code' => '650000', 'sort_order' => 1],
            ['name' => '克拉玛依市', 'code' => '650200', 'level' => 2, 'province_code' => '650000', 'sort_order' => 2],
            ['name' => '吐鲁番市', 'code' => '650400', 'level' => 2, 'province_code' => '650000', 'sort_order' => 3],
            ['name' => '哈密市', 'code' => '650500', 'level' => 2, 'province_code' => '650000', 'sort_order' => 4],
            ['name' => '昌吉回族自治州', 'code' => '652300', 'level' => 2, 'province_code' => '650000', 'sort_order' => 5],
            ['name' => '博尔塔拉蒙古自治州', 'code' => '652700', 'level' => 2, 'province_code' => '650000', 'sort_order' => 6],
            ['name' => '巴音郭楞蒙古自治州', 'code' => '652800', 'level' => 2, 'province_code' => '650000', 'sort_order' => 7],
            ['name' => '阿克苏地区', 'code' => '652900', 'level' => 2, 'province_code' => '650000', 'sort_order' => 8],
            ['name' => '克孜勒苏柯尔克孜自治州', 'code' => '653000', 'level' => 2, 'province_code' => '650000', 'sort_order' => 9],
            ['name' => '喀什地区', 'code' => '653100', 'level' => 2, 'province_code' => '650000', 'sort_order' => 10],
            ['name' => '和田地区', 'code' => '653200', 'level' => 2, 'province_code' => '650000', 'sort_order' => 11],
            ['name' => '伊犁哈萨克自治州', 'code' => '654000', 'level' => 2, 'province_code' => '650000', 'sort_order' => 12],
            ['name' => '塔城地区', 'code' => '654200', 'level' => 2, 'province_code' => '650000', 'sort_order' => 13],
            ['name' => '阿勒泰地区', 'code' => '654300', 'level' => 2, 'province_code' => '650000', 'sort_order' => 14],
            ['name' => '石河子市', 'code' => '659001', 'level' => 2, 'province_code' => '650000', 'sort_order' => 15],
            ['name' => '阿拉尔市', 'code' => '659002', 'level' => 2, 'province_code' => '650000', 'sort_order' => 16],
            ['name' => '图木舒克市', 'code' => '659003', 'level' => 2, 'province_code' => '650000', 'sort_order' => 17],
            ['name' => '五家渠市', 'code' => '659004', 'level' => 2, 'province_code' => '650000', 'sort_order' => 18],
            ['name' => '北屯市', 'code' => '659005', 'level' => 2, 'province_code' => '650000', 'sort_order' => 19],
            ['name' => '铁门关市', 'code' => '659006', 'level' => 2, 'province_code' => '650000', 'sort_order' => 20],
            ['name' => '双河市', 'code' => '659007', 'level' => 2, 'province_code' => '650000', 'sort_order' => 21],
            ['name' => '可克达拉市', 'code' => '659008', 'level' => 2, 'province_code' => '650000', 'sort_order' => 22],
            ['name' => '昆玉市', 'code' => '659009', 'level' => 2, 'province_code' => '650000', 'sort_order' => 23],
            ['name' => '胡杨河市', 'code' => '659010', 'level' => 2, 'province_code' => '650000', 'sort_order' => 24],
            ['name' => '新星市', 'code' => '659011', 'level' => 2, 'province_code' => '650000', 'sort_order' => 25],
            
            // 台湾省
            ['name' => '台北市', 'code' => '710100', 'level' => 2, 'province_code' => '710000', 'sort_order' => 1],
            ['name' => '高雄市', 'code' => '710200', 'level' => 2, 'province_code' => '710000', 'sort_order' => 2],
            ['name' => '基隆市', 'code' => '710300', 'level' => 2, 'province_code' => '710000', 'sort_order' => 3],
            ['name' => '台中市', 'code' => '710400', 'level' => 2, 'province_code' => '710000', 'sort_order' => 4],
            ['name' => '台南市', 'code' => '710500', 'level' => 2, 'province_code' => '710000', 'sort_order' => 5],
            ['name' => '新竹市', 'code' => '710600', 'level' => 2, 'province_code' => '710000', 'sort_order' => 6],
            ['name' => '嘉义市', 'code' => '710700', 'level' => 2, 'province_code' => '710000', 'sort_order' => 7],
            ['name' => '台北县', 'code' => '712300', 'level' => 2, 'province_code' => '710000', 'sort_order' => 8],
            ['name' => '宜兰县', 'code' => '712400', 'level' => 2, 'province_code' => '710000', 'sort_order' => 9],
            ['name' => '桃园县', 'code' => '712500', 'level' => 2, 'province_code' => '710000', 'sort_order' => 10],
            ['name' => '新竹县', 'code' => '712600', 'level' => 2, 'province_code' => '710000', 'sort_order' => 11],
            ['name' => '苗栗县', 'code' => '712700', 'level' => 2, 'province_code' => '710000', 'sort_order' => 12],
            ['name' => '台中县', 'code' => '712800', 'level' => 2, 'province_code' => '710000', 'sort_order' => 13],
            ['name' => '彰化县', 'code' => '712900', 'level' => 2, 'province_code' => '710000', 'sort_order' => 14],
            ['name' => '南投县', 'code' => '713000', 'level' => 2, 'province_code' => '710000', 'sort_order' => 15],
            ['name' => '云林县', 'code' => '713100', 'level' => 2, 'province_code' => '710000', 'sort_order' => 16],
            ['name' => '嘉义县', 'code' => '713200', 'level' => 2, 'province_code' => '710000', 'sort_order' => 17],
            ['name' => '台南县', 'code' => '713300', 'level' => 2, 'province_code' => '710000', 'sort_order' => 18],
            ['name' => '高雄县', 'code' => '713400', 'level' => 2, 'province_code' => '710000', 'sort_order' => 19],
            ['name' => '屏东县', 'code' => '713500', 'level' => 2, 'province_code' => '710000', 'sort_order' => 20],
            ['name' => '台东县', 'code' => '713600', 'level' => 2, 'province_code' => '710000', 'sort_order' => 21],
            ['name' => '花莲县', 'code' => '713700', 'level' => 2, 'province_code' => '710000', 'sort_order' => 22],
            ['name' => '澎湖县', 'code' => '713800', 'level' => 2, 'province_code' => '710000', 'sort_order' => 23],
            
            // 香港特别行政区
            ['name' => '香港岛', 'code' => '810100', 'level' => 2, 'province_code' => '810000', 'sort_order' => 1],
            ['name' => '九龙', 'code' => '810200', 'level' => 2, 'province_code' => '810000', 'sort_order' => 2],
            ['name' => '新界', 'code' => '810300', 'level' => 2, 'province_code' => '810000', 'sort_order' => 3],
            
            // 澳门特别行政区
            ['name' => '澳门半岛', 'code' => '820100', 'level' => 2, 'province_code' => '820000', 'sort_order' => 1],
            ['name' => '凼仔', 'code' => '820200', 'level' => 2, 'province_code' => '820000', 'sort_order' => 2],
            ['name' => '路环', 'code' => '820300', 'level' => 2, 'province_code' => '820000', 'sort_order' => 3],
        ];
    }
}