<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

Route::get('/NCh85NioN3.txt', function () {
    return response('6ade8d89c63cc953f42cfba6dee94b0c', 200)
        ->header('Content-Type', 'text/plain');
});

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('welcome');

Route::get('home', function () {
    return Inertia::render('my');
})->name('home');

Route::get('service', function () {
    return Inertia::render('customer-service');
})->name('service');

Route::get('members', function () {
    return Inertia::render('members');
})->name('members');

// 临时测试路由（不需要认证）
Route::get('my-test', function () {
    return Inertia::render('my');
})->name('my-test');

// 临时测试dashboard数据路由（不需要认证）
Route::get('dashboard-test', function () {
    // 获取所有省份数据（排除台湾、香港、澳门）
    $provinces = DB::table('regions')
        ->where('level', 1)
        ->whereNotIn('id', [32, 33, 34]) // 排除台湾、香港、澳门
        ->select('id', 'name', 'code')
        ->orderBy('id')
        ->get();
    
    // 获取热门城市
    $hotCities = DB::table('regions')
        ->where('is_hot', true)
        ->select('id', 'name', 'parent_id')
        ->get()
        ->map(function($city) {
            return [
                'id' => $city->id,
                'name' => $city->name,
                'full_name' => $city->name,
                'parent_id' => $city->parent_id,
                'has_districts' => false
            ];
        });

    return Inertia::render('dashboard', [
        'provinces' => $provinces->map(function($province) {
            $provinceName = $province->name;
            $simplifiedName = rtrim($provinceName, '省市');
            $simplifiedName = rtrim($simplifiedName, '自治区');
            $simplifiedName = str_replace(['壮族', '维吾尔', '回族'], '', $simplifiedName);
            $simplifiedName = trim($simplifiedName);
            
            $colorMap = ['北京' => 'bg-purple-500', '上海' => 'bg-orange-500', '天津' => 'bg-blue-500', '重庆' => 'bg-red-500', '香港' => 'bg-rose-500', '澳门' => 'bg-amber-500'];
            $shortNameMap = ['北京' => '京', '上海' => '沪', '天津' => '津', '重庆' => '渝', '香港' => '港', '澳门' => '澳'];
            
            return [
                'id' => $province->id,
                'name' => $provinceName,
                'shortName' => $shortNameMap[$simplifiedName] ?? $provinceName[0],
                'color' => $colorMap[$simplifiedName] ?? 'bg-gray-500',
                'cities' => []
            ];
        }),
        'hotGroups' => [],
        'hotCities' => $hotCities
    ]);
})->name('dashboard-test');

// 临时简化dashboard测试（不需要认证）
Route::get('dashboard-simple', function () {
    try {
        // 获取所有省份数据（排除台湾）
        $provinces = DB::table('regions')
            ->where('level', 1)
            ->whereNotIn('id', [32]) // 排除台湾
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();
        
        return response()->json([
            'success' => true,
            'provinces_count' => count($provinces),
            'first_province' => $provinces->first()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('dashboard-simple');

// 临时调试dashboard逻辑（不需要认证）
Route::get('dashboard-debug', function () {
    try {
        // 获取所有省份数据（排除台湾）
        $provinces = DB::table('regions')
            ->where('level', 1)
            ->whereNotIn('id', [32]) // 排除台湾
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();
        
        $debug = ['step1' => 'provinces loaded: ' . count($provinces)];
        
        // 获取热门群组数据（每个省份/直辖市的前5个群）
        $hotGroups = [];
        foreach ($provinces->take(2) as $province) {
            $debug['step2_province'] = $province->name;
            
            $groups = DB::table('wechat_groups')
                ->join('regions as cities', 'wechat_groups.city_id', '=', 'cities.id')
                ->where(function($query) use ($province) {
                    $query->where('cities.parent_id', $province->id)
                          ->orWhere('wechat_groups.city_id', $province->id); // 处理直辖市
                })
                ->where('wechat_groups.is_active', true)
                ->select([
                    'wechat_groups.id',
                    'wechat_groups.group_name',
                    'wechat_groups.nickname',
                    'wechat_groups.current_members',
                    'wechat_groups.capacity',
                    'wechat_groups.description',
                    'wechat_groups.tags',
                    'wechat_groups.is_hot',
                    'cities.name as city_name'
                ])
                ->orderBy('wechat_groups.is_hot', 'desc')
                ->orderBy('wechat_groups.current_members', 'desc')
                ->limit(5)
                ->get();
            
            $debug['step3_groups_for_' . $province->name] = count($groups);
            
            // 只有当该省份有群组数据时才添加到热门群列表
            foreach ($groups as $group) {
                $hotGroups[] = [
                    'id' => $group->id,
                    'name' => $group->group_name,
                    'nickname' => $group->nickname,
                    'city' => $province->name,
                    'city_full' => $group->city_name,
                    'province' => $province->name,
                    'memberCount' => $group->current_members,
                    'capacity' => $group->capacity,
                    'description' => $group->description,
                    'tags' => $group->tags ? explode(',', $group->tags) : [],
                    'is_hot' => $group->is_hot,
                    'thumbnail' => '/images/groups/qun' . ($group->id % 10) . '.jpg'
                ];
            }
        }
        
        $debug['step4'] = 'hotGroups created: ' . count($hotGroups);
        
        return response()->json([
            'success' => true,
            'debug' => $debug,
            'hotGroups_sample' => array_slice($hotGroups, 0, 2)
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('dashboard-debug');

// 临时测试省份数据处理（不需要认证）
Route::get('dashboard-provinces-debug', function () {
    try {
        // 获取所有省份数据（排除台湾）
        $provinces = DB::table('regions')
            ->where('level', 1)
            ->whereNotIn('id', [32]) // 排除台湾
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();
        
        $debug = ['step1' => 'provinces loaded: ' . count($provinces)];
        
        // 处理省份数据格式
        $provincesData = [];
        $colorMap = [
            '北京' => 'bg-purple-500',
            '上海' => 'bg-orange-500', 
            '天津' => 'bg-blue-500',
            '重庆' => 'bg-red-500',
            '河北' => 'bg-lime-500',
            '山西' => 'bg-orange-500',
            '内蒙古' => 'bg-emerald-500',
            '辽宁' => 'bg-cyan-500',
            '吉林' => 'bg-teal-500',
            '黑龙江' => 'bg-slate-600',
            '江苏' => 'bg-indigo-500',
            '浙江' => 'bg-teal-500',
            '安徽' => 'bg-green-500',
            '福建' => 'bg-blue-500',
            '江西' => 'bg-orange-500',
            '山东' => 'bg-yellow-500',
            '河南' => 'bg-red-500',
            '湖北' => 'bg-cyan-500',
            '湖南' => 'bg-pink-500',
            '广东' => 'bg-red-500',
            '广西' => 'bg-green-500',
            '海南' => 'bg-blue-500',
            '四川' => 'bg-blue-500',
            '贵州' => 'bg-purple-500',
            '云南' => 'bg-green-500',
            '西藏' => 'bg-yellow-500',
            '陕西' => 'bg-gray-500',
            '甘肃' => 'bg-orange-500',
            '青海' => 'bg-blue-500',
            '宁夏' => 'bg-indigo-500',
            '新疆' => 'bg-emerald-500',
            '香港' => 'bg-rose-500',
            '澳门' => 'bg-amber-500'
        ];
        
        $shortNameMap = [
            '北京' => '京',
            '上海' => '沪',
            '天津' => '津',
            '重庆' => '渝',
            '河北' => '冀',
            '山西' => '晋',
            '内蒙古' => '蒙',
            '辽宁' => '辽',
            '吉林' => '吉',
            '黑龙江' => '黑',
            '江苏' => '苏',
            '浙江' => '浙',
            '安徽' => '皖',
            '福建' => '闽',
            '江西' => '赣',
            '山东' => '鲁',
            '河南' => '豫',
            '湖北' => '鄂',
            '湖南' => '湘',
            '广东' => '粤',
            '广西' => '桂',
            '海南' => '琼',
            '四川' => '川',
            '贵州' => '黔',
            '云南' => '滇',
            '西藏' => '藏',
            '陕西' => '陕',
            '甘肃' => '甘',
            '青海' => '青',
            '宁夏' => '宁',
            '新疆' => '新',
            '香港' => '港',
            '澳门' => '澳'
        ];
        
        foreach ($provinces->take(5) as $province) {
            $debug['processing_province'] = $province->name;
            
            // 保持原始的省份名称
            $provinceName = $province->name;
            
            // 创建简化名称用于映射（去掉后缀）
            $simplifiedName = $provinceName;
            $simplifiedName = rtrim($simplifiedName, '省市');
            $simplifiedName = rtrim($simplifiedName, '自治区');
            $simplifiedName = str_replace(['壮族', '维吾尔', '回族'], '', $simplifiedName);
            $simplifiedName = trim($simplifiedName);
            
            $debug['simplified_name'] = $simplifiedName;
            
            // 获取该省份的城市/区县
            $cities = DB::table('regions')
                ->where('parent_id', $province->id)
                ->where('level', '>', 1)
                ->select('name')
                ->get()
                ->pluck('name')
                ->map(function($name) {
                    return $name;
                })
                ->toArray();
            
            $debug['cities_count'] = count($cities);
            
            $provincesData[] = [
                'id' => $province->id,
                'name' => $provinceName,
                'shortName' => $shortNameMap[$simplifiedName] ?? $provinceName[0],
                'color' => $colorMap[$simplifiedName] ?? 'bg-gray-500',
                'cities' => $cities
            ];
        }
        
        $debug['provinces_data_count'] = count($provincesData);
        
        return response()->json([
            'success' => true,
            'debug' => $debug,
            'sample_province_data' => $provincesData[0] ?? null
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('dashboard-provinces-debug');

// 临时修复dashboard路由（不需要认证）
Route::get('dashboard-fixed', function () {
    // 获取所有省份数据（排除台湾、香港、澳门）
    $provinces = DB::table('regions')
        ->where('level', 1)
        ->whereNotIn('id', [32, 33, 34]) // 排除台湾、香港、澳门
        ->select('id', 'name', 'code')
        ->orderBy('id')
        ->get();
    
    // 获取热门群组数据（每个省份/直辖市的前5个群）
    $hotGroups = [];
    foreach ($provinces as $province) {
        $groups = DB::table('wechat_groups')
            ->join('regions as cities', 'wechat_groups.city_id', '=', 'cities.id')
            ->where(function($query) use ($province) {
                $query->where('cities.parent_id', $province->id)
                      ->orWhere('wechat_groups.city_id', $province->id); // 处理直辖市
            })
            ->where('wechat_groups.is_active', true)
            ->select([
                'wechat_groups.id',
                'wechat_groups.group_name',
                'wechat_groups.nickname',
                'wechat_groups.current_members',
                'wechat_groups.capacity',
                'wechat_groups.description',
                'wechat_groups.tags',
                'wechat_groups.is_hot',
                'cities.name as city_name'
            ])
            ->orderBy('wechat_groups.is_hot', 'desc')
            ->orderBy('wechat_groups.current_members', 'desc')
            ->limit(5)
            ->get();
        
        // 只有当该省份有群组数据时才添加到热门群列表
        foreach ($groups as $group) {
            $hotGroups[] = [
                'id' => $group->id,
                'name' => $group->group_name,
                'nickname' => $group->nickname,
                'city' => $province->name,
                'city_full' => $group->city_name,
                'province' => $province->name,
                'memberCount' => $group->current_members,
                'capacity' => $group->capacity,
                'description' => $group->description,
                'tags' => $group->tags ? explode(',', $group->tags) : [],
                'is_hot' => $group->is_hot,
                'thumbnail' => '/images/groups/qun' . ($group->id % 10) . '.jpg'
            ];
        }
    }
    
    // 处理省份数据格式
    $provincesData = [];
    $colorMap = [
        '北京' => 'bg-purple-500',
        '上海' => 'bg-orange-500', 
        '天津' => 'bg-blue-500',
        '重庆' => 'bg-red-500',
        '河北' => 'bg-gray-500',
        '山西' => 'bg-slate-500',
        '内蒙古' => 'bg-emerald-500',
        '辽宁' => 'bg-cyan-500',
        '吉林' => 'bg-teal-500',
        '黑龙江' => 'bg-slate-600',
        '江苏' => 'bg-indigo-500',
        '浙江' => 'bg-teal-500',
        '安徽' => 'bg-green-500',
        '福建' => 'bg-blue-500',
        '江西' => 'bg-orange-500',
        '山东' => 'bg-yellow-500',
        '河南' => 'bg-red-500',
        '湖北' => 'bg-cyan-500',
        '湖南' => 'bg-pink-500',
        '广东' => 'bg-red-500',
        '广西' => 'bg-green-500',
        '海南' => 'bg-blue-500',
        '四川' => 'bg-blue-500',
        '贵州' => 'bg-purple-500',
        '云南' => 'bg-green-500',
        '西藏' => 'bg-yellow-500',
        '陕西' => 'bg-gray-500',
        '甘肃' => 'bg-orange-500',
        '青海' => 'bg-blue-500',
        '宁夏' => 'bg-indigo-500',
        '新疆' => 'bg-emerald-500',
        '香港' => 'bg-rose-500',
        '澳门' => 'bg-amber-500'
    ];
    
    $shortNameMap = [
        '北京' => '京',
        '上海' => '沪',
        '天津' => '津',
        '重庆' => '渝',
        '河北' => '冀',
        '山西' => '晋',
        '内蒙古' => '蒙',
        '辽宁' => '辽',
        '吉林' => '吉',
        '黑龙江' => '黑',
        '江苏' => '苏',
        '浙江' => '浙',
        '安徽' => '皖',
        '福建' => '闽',
        '江西' => '赣',
        '山东' => '鲁',
        '河南' => '豫',
        '湖北' => '鄂',
        '湖南' => '湘',
        '广东' => '粤',
        '广西' => '桂',
        '海南' => '琼',
        '四川' => '川',
        '贵州' => '黔',
        '云南' => '滇',
        '西藏' => '藏',
        '陕西' => '陕',
        '甘肃' => '甘',
        '青海' => '青',
        '宁夏' => '宁',
        '新疆' => '新',
        '香港' => '港',
        '澳门' => '澳'
    ];
    
    foreach ($provinces as $province) {
        // 保持原始的省份名称
        $provinceName = $province->name;
        
        // 创建简化名称用于映射（去掉后缀）
        $simplifiedName = $provinceName;
        $simplifiedName = rtrim($simplifiedName, '省市');
        $simplifiedName = rtrim($simplifiedName, '自治区');
        $simplifiedName = str_replace(['壮族', '维吾尔', '回族'], '', $simplifiedName);
        $simplifiedName = trim($simplifiedName);
        
        // 获取该省份的城市/区县
        $cities = DB::table('regions')
            ->where('parent_id', $province->id)
            ->where('level', '>', 1)
            ->select('name')
            ->get()
            ->pluck('name')
            ->map(function($name) {
                return $name;
            })
            ->toArray();
        
        $provincesData[] = [
            'id' => $province->id,
            'name' => $provinceName,
            'shortName' => $shortNameMap[$simplifiedName] ?? $provinceName[0],
            'color' => $colorMap[$simplifiedName] ?? 'bg-gray-500',
            'cities' => $cities
        ];
    }
    
    // 获取热门城市
    $hotCities = DB::table('regions')
        ->where('is_hot', true)
        ->select('id', 'name', 'parent_id')
        ->get()
        ->map(function($city) {
            // 检查该城市是否有下级县区
            $hasDistricts = DB::table('regions')
                ->where('parent_id', $city->id)
                ->where('level', '3')
                ->exists();
            
            return [
                'id' => $city->id,
                'name' => $city->name,
                'full_name' => $city->name,
                'parent_id' => $city->parent_id,
                'has_districts' => $hasDistricts
            ];
        });

    return Inertia::render('dashboard', [
        'provinces' => $provincesData,
        'hotGroups' => $hotGroups,
        'hotCities' => $hotCities
    ]);
})->name('dashboard-fixed');

// 临时测试API端点（不需要认证）
Route::get('api-test/provinces/{provinceId}/cities', function ($provinceId) {
    // 检查是否是直辖市
    $province = DB::table('regions')->where('id', $provinceId)->first();
    if (!$province) {
        return response()->json(['error' => '省份不存在'], 404);
    }
    
    $isDirectMunicipality = $province && in_array($province->name, ['北京市', '上海市', '天津市', '重庆市']);
    
    if ($isDirectMunicipality) {
        // 对于直辖市，返回市级数据和县区数据
        $cities = collect();
        
        // 首先添加市级数据
        $municipalityCity = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '2') // 市级
            ->select('id', 'name', 'code')
            ->first();
        
        if ($municipalityCity) {
            $cities->push([
                'id' => $municipalityCity->id,
                'name' => $municipalityCity->name,
                'full_name' => $municipalityCity->name,
                'code' => $municipalityCity->code,
                'has_districts' => false, // 市级选项直接进群
                'icon' => '/images/groups/qun' . ($municipalityCity->id % 10) . '.jpg'
            ]);
        }
        
        // 然后添加县区数据
        $districts = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '3') // 县区级
            ->select('id', 'name', 'code', 'sort_order')
            ->orderBy('sort_order')
            ->orderBy('name') // 相同sort_order时按名称排序
            ->get()
            ->map(function($district) {
                return [
                    'id' => $district->id,
                    'name' => $district->name, // 保留"区"、"县"后缀
                    'full_name' => $district->name,
                    'code' => $district->code,
                    'has_districts' => false, // 区县级没有下级
                    'icon' => '/images/groups/qun' . ($district->id % 10) . '.jpg'
                ];
            });
        
        $cities = $cities->merge($districts);
    } else {
        // 对于省份，返回市级
        $cities = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '2') // 市级
            ->select('id', 'name', 'code', 'sort_order')
            ->orderBy('sort_order')
            ->orderBy('name') // 相同sort_order时按名称排序
            ->get()
            ->map(function($city) {
                // 检查该城市是否有下级县区
                $hasDistricts = DB::table('regions')
                    ->where('parent_id', $city->id)
                    ->where('level', '3') // 县区级
                    ->exists();
                
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'full_name' => $city->name,
                    'code' => $city->code,
                    'has_districts' => $hasDistricts,
                    'icon' => '/images/groups/qun' . ($city->id % 10) . '.jpg'
                ];
            });
    }
        
    return response()->json([
        'cities' => $cities
    ]);
})->name('api-test.provinces.cities');

// Dashboard 路由（不需要认证）
Route::get('dashboard', function () {
    try {
        // 获取所有省份数据（排除台湾）
        $provinces = DB::table('regions')
            ->where('level', 1)
            ->whereNotIn('id', [32]) // 排除台湾
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();
        
        // 简化版本：只获取基本省份数据和空数组
        $provincesData = [];
        $colorMap = [
            '北京' => 'bg-purple-500',
            '上海' => 'bg-orange-500', 
            '天津' => 'bg-blue-500',
            '重庆' => 'bg-red-500',
            '河北' => 'bg-lime-500',
            '山西' => 'bg-orange-500',
            '内蒙古' => 'bg-emerald-500',
            '辽宁' => 'bg-cyan-500',
            '吉林' => 'bg-teal-500',
            '黑龙江' => 'bg-slate-600',
            '江苏' => 'bg-indigo-500',
            '浙江' => 'bg-teal-500',
            '安徽' => 'bg-green-500',
            '福建' => 'bg-blue-500',
            '江西' => 'bg-orange-500',
            '山东' => 'bg-yellow-500',
            '河南' => 'bg-red-500',
            '湖北' => 'bg-cyan-500',
            '湖南' => 'bg-pink-500',
            '广东' => 'bg-red-500',
            '广西' => 'bg-green-500',
            '海南' => 'bg-blue-500',
            '四川' => 'bg-blue-500',
            '贵州' => 'bg-purple-500',
            '云南' => 'bg-green-500',
            '西藏' => 'bg-yellow-500',
            '陕西' => 'bg-gray-500',
            '甘肃' => 'bg-orange-500',
            '青海' => 'bg-blue-500',
            '宁夏' => 'bg-indigo-500',
            '新疆' => 'bg-emerald-500',
            '香港' => 'bg-rose-500',
            '澳门' => 'bg-amber-500'
        ];
        
        $shortNameMap = [
            '北京' => '京',
            '上海' => '沪',
            '天津' => '津',
            '重庆' => '渝',
            '河北' => '冀',
            '山西' => '晋',
            '内蒙古' => '蒙',
            '辽宁' => '辽',
            '吉林' => '吉',
            '黑龙江' => '黑',
            '江苏' => '苏',
            '浙江' => '浙',
            '安徽' => '皖',
            '福建' => '闽',
            '江西' => '赣',
            '山东' => '鲁',
            '河南' => '豫',
            '湖北' => '鄂',
            '湖南' => '湘',
            '广东' => '粤',
            '广西' => '桂',
            '海南' => '琼',
            '四川' => '川',
            '贵州' => '黔',
            '云南' => '滇',
            '西藏' => '藏',
            '陕西' => '陕',
            '甘肃' => '甘',
            '青海' => '青',
            '宁夏' => '宁',
            '新疆' => '新',
            '香港' => '港',
            '澳门' => '澳'
        ];
        
        foreach ($provinces as $province) {
            // 保持原始的省份名称，并确保UTF-8编码正确
            $provinceName = mb_convert_encoding($province->name, 'UTF-8', 'UTF-8');
            
            // 创建简化名称用于映射（去掉后缀）
            $simplifiedName = $provinceName;
            $simplifiedName = mb_substr($simplifiedName, -1) === '省' ? mb_substr($simplifiedName, 0, -1) : $simplifiedName;
            $simplifiedName = mb_substr($simplifiedName, -1) === '市' ? mb_substr($simplifiedName, 0, -1) : $simplifiedName;
            $simplifiedName = mb_substr($simplifiedName, -3) === '自治区' ? mb_substr($simplifiedName, 0, -3) : $simplifiedName;
            $simplifiedName = mb_substr($simplifiedName, -5) === '特别行政区' ? mb_substr($simplifiedName, 0, -5) : $simplifiedName;
            $simplifiedName = str_replace(['壮族', '维吾尔', '回族'], '', $simplifiedName);
            $simplifiedName = trim($simplifiedName);
            
            // 确保所有字符串都是有效的UTF-8
            $shortName = isset($shortNameMap[$simplifiedName]) ? $shortNameMap[$simplifiedName] : mb_substr($provinceName, 0, 1, 'UTF-8');
            $color = isset($colorMap[$simplifiedName]) ? $colorMap[$simplifiedName] : 'bg-gray-500';
            
            $provincesData[] = [
                'id' => (int)$province->id,
                'name' => $provinceName,
                'shortName' => $shortName,
                'color' => $color,
                'cities' => []
            ];
        }
        
        // 获取热门城市
        $hotCities = DB::table('regions')
            ->where('is_hot', true)
            ->select('id', 'name', 'parent_id')
            ->get()
            ->map(function($city) {
                // 检查该城市是否有下级县区
                $hasDistricts = DB::table('regions')
                    ->where('parent_id', $city->id)
                    ->where('level', '3')
                    ->exists();
                
                return [
                    'id' => $city->id,
                    'name' => mb_convert_encoding($city->name, 'UTF-8', 'UTF-8'),
                    'full_name' => mb_convert_encoding($city->name, 'UTF-8', 'UTF-8'),
                    'parent_id' => $city->parent_id,
                    'has_districts' => $hasDistricts
                ];
            });

        // 确保JSON编码使用正确的选项
        $data = [
            'provinces' => $provincesData,
            'hotGroups' => [],
            'hotCities' => $hotCities->toArray()
        ];
        
        // 验证数据可以正确序列化为JSON
        $jsonTest = json_encode($data, JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON encoding error: ' . json_last_error_msg());
        }
        
        return Inertia::render('dashboard', $data);
    } catch (Exception $e) {
        // 错误处理：返回最基本的数据
        return Inertia::render('dashboard', [
            'provinces' => [],
            'hotGroups' => [],
            'hotCities' => []
        ]);
    }
})->name('dashboard');

// API路由（不需要认证）
// 获取省份的城市列表
Route::get('api/provinces/{provinceId}/cities', function ($provinceId) {
    // 检查是否是直辖市
    $province = DB::table('regions')->where('id', $provinceId)->first();
    $isDirectMunicipality = $province && in_array($province->name, ['北京市', '上海市', '天津市', '重庆市']);
    
    if ($isDirectMunicipality) {
        // 对于直辖市，返回市级数据和县区数据
        $cities = collect();
        
        // 首先添加市级数据
        $municipalityCity = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '2') // 市级
            ->select('id', 'name', 'code')
            ->first();
        
        if ($municipalityCity) {
            $cities->push([
                'id' => $municipalityCity->id,
                'name' => $municipalityCity->name,
                'full_name' => $municipalityCity->name,
                'code' => $municipalityCity->code,
                'has_districts' => false, // 市级选项直接进群
                'icon' => '/images/groups/qun' . ($municipalityCity->id % 10) . '.jpg'
            ]);
        }
        
        // 然后添加县区数据
        $districts = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '3') // 县区级
            ->select('id', 'name', 'code', 'sort_order')
            ->orderBy('sort_order')
            ->orderBy('name') // 相同sort_order时按名称排序
            ->get()
            ->map(function($district) {
                return [
                    'id' => $district->id,
                    'name' => $district->name, // 保留"区"、"县"后缀
                    'full_name' => $district->name,
                    'code' => $district->code,
                    'has_districts' => false, // 区县级没有下级
                    'icon' => '/images/groups/qun' . ($district->id % 10) . '.jpg'
                ];
            });
        
        $cities = $cities->merge($districts);
    } else {
        // 对于省份，返回市级
        $cities = DB::table('regions')
            ->where('parent_id', $provinceId)
            ->where('level', '2') // 市级
            ->select('id', 'name', 'code', 'sort_order')
            ->orderBy('sort_order')
            ->orderBy('name') // 相同sort_order时按名称排序
            ->get()
            ->map(function($city) {
                // 检查该城市是否有下级县区
                $hasDistricts = DB::table('regions')
                    ->where('parent_id', $city->id)
                    ->where('level', '3') // 县区级
                    ->exists();
                
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'full_name' => $city->name,
                    'code' => $city->code,
                    'has_districts' => $hasDistricts,
                    'icon' => '/images/groups/qun' . ($city->id % 10) . '.jpg'
                ];
            });
    }
        
    return response()->json([
        'cities' => $cities
    ]);
})->name('api.provinces.cities');

// 获取城市的县区列表（包括市级选项）
Route::get('api/cities/{cityId}/districts', function ($cityId) {
    // 获取城市信息
    $city = DB::table('regions')
        ->where('id', $cityId)
        ->first();
    
    if (!$city) {
        return response()->json(['error' => '城市不存在'], 404);
    }
    
    // 检查是否有市级群组
    $hasCityGroups = DB::table('wechat_groups')
        ->where('city_id', $cityId)
        ->whereNull('district_id')
        ->where('is_active', true)
        ->exists();
    
    // 获取县区列表
    $districts = DB::table('regions')
        ->where('parent_id', $cityId)
        ->where('level', '3') // 县区级
        ->select('id', 'name', 'code', 'sort_order')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
                'full_name' => $district->name,
                'code' => $district->code
            ];
        });
    
    $allDistricts = [];
    
    // 如果没有县区记录，或者有市级群组，则添加市级选项
    if ($districts->isEmpty() || $hasCityGroups) {
        $allDistricts[] = [
            'id' => $city->id,
            'name' => $city->name,
            'full_name' => $city->name,
            'code' => $city->code
        ];
    }
    
    // 添加县区选项
    $allDistricts = array_merge($allDistricts, $districts->toArray());
    
    return response()->json([
        'districts' => $allDistricts
    ]);
})->name('api.cities.districts');

// 获取城市的市级和县区级选项（用于热门城市）
Route::get('api/cities/{cityId}/city-districts', function ($cityId) {
    // 获取城市信息
    $city = DB::table('regions')
        ->where('id', $cityId)
        ->first();
    
    if (!$city) {
        return response()->json(['error' => '城市不存在'], 404);
    }
    
    // 检查是否有市级群组
    $hasCityGroups = DB::table('wechat_groups')
        ->where('city_id', $cityId)
        ->whereNull('district_id')
        ->where('is_active', true)
        ->exists();
    
    // 获取县区列表
    $districts = DB::table('regions')
        ->where('parent_id', $cityId)
        ->where('level', '3') // 县区级
        ->select('id', 'name', 'code', 'sort_order')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
                'full_name' => $district->name,
                'code' => $district->code,
                'type' => 'district'
            ];
        });
    
    $options = [];
    
    // 对于热门城市，始终显示市级选项
    $options[] = [
        'id' => $city->id,
        'name' => $city->name,
        'full_name' => $city->name,
        'code' => $city->code,
        'type' => 'city'
    ];
    
    // 添加县区选项
    $options = array_merge($options, $districts->toArray());
    
    return response()->json([
        'options' => $options,
        'city_name' => $city->name
    ]);
})->name('api.cities.city-districts');

// 搜索路由 - 搜索地区（不需要认证）
Route::get('search', function () {
    $query = request('q', '');
    $results = [];
    
    if ($query) {
        // 搜索城市
        $cities = DB::table('regions as cities')
            ->leftJoin('regions as provinces', 'cities.parent_id', '=', 'provinces.id')
            ->where('cities.level', '2') // 市级
            ->where(function($q) use ($query) {
                $q->where('cities.name', 'like', '%' . $query . '%')
                  ->orWhere('provinces.name', 'like', '%' . $query . '%');
            })
            ->select([
                'cities.id',
                'cities.name as city_name',
                'cities.code',
                'cities.sort_order',
                'provinces.name as province_name',
                'provinces.id as province_id'
            ])
            ->orderBy('cities.sort_order')
            ->orderBy('cities.name')
            ->limit(10)
            ->get();
        
        // 搜索县区
        $districts = DB::table('regions as districts')
            ->leftJoin('regions as cities', 'districts.parent_id', '=', 'cities.id')
            ->leftJoin('regions as provinces', 'cities.parent_id', '=', 'provinces.id')
            ->where('districts.level', '3') // 县区级
            ->where(function($q) use ($query) {
                $q->where('districts.name', 'like', '%' . $query . '%')
                  ->orWhere('cities.name', 'like', '%' . $query . '%')
                  ->orWhere('provinces.name', 'like', '%' . $query . '%');
            })
            ->select([
                'districts.id',
                'districts.name as district_name',
                'districts.code',
                'districts.sort_order',
                'cities.name as city_name',
                'cities.id as city_id',
                'provinces.name as province_name',
                'provinces.id as province_id'
            ])
            ->orderBy('districts.sort_order')
            ->orderBy('districts.name')
            ->limit(10)
            ->get();
        
        // 组合结果
        $results = [];
        
        // 添加城市结果
        foreach ($cities as $city) {
            $results[] = [
                'id' => $city->id,
                'name' => $city->city_name,
                'full_name' => $city->city_name,
                'type' => 'city',
                'city' => $city->city_name,
                'province' => $city->province_name ?? '',
                'province_id' => $city->province_id,
                'icon' => '/images/groups/qun' . ($city->id % 10) . '.jpg'
            ];
        }
        
        // 添加县区结果
        foreach ($districts as $district) {
            $results[] = [
                'id' => $district->id,
                'name' => $district->district_name,
                'full_name' => $district->district_name,
                'type' => 'district',
                'city' => $district->city_name,
                'city_id' => $district->city_id,
                'province' => $district->province_name ?? '',
                'province_id' => $district->province_id,
                'icon' => '/images/groups/qun' . ($district->id % 10) . '.jpg'
            ];
        }
    }
    
    return response()->json([
        'results' => $results,
        'total' => count($results)
    ]);
})->name('search');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Placeholder routes for mobile tabs
    Route::get('hall', function () {
        return Inertia::render('hall');
    })->name('hall');
    
    Route::get('latest', function () {
        return Inertia::render('latest');
    })->name('latest');
    
    Route::get('my-published', function () {
        return Inertia::render('my');
    })->name('my-published');
    
    Route::get('my-recommendations', function () {
        return Inertia::render('my-recommendations');
    })->name('my-recommendations');
    
    Route::get('my-wallet', function () {
        return Inertia::render('my-wallet');
    })->name('my-wallet');
    
});

// Additional public routes
Route::get('memberlist', function () {
    return Inertia::render('members');
})->name('memberlist');

Route::get('usercenter', function () {
    return Inertia::render('usercenter');
})->name('usercenter');

Route::get('groups/{province}/{city}', function ($province, $city) {
    // 获取省份信息
    $provinceData = DB::table('regions')
        ->where('name', $province . '省')
        ->orWhere('name', $province)
        ->first();
    
    // 获取城市信息
    $cityData = DB::table('regions')
        ->where('name', $city . '市')
        ->orWhere('name', $city)
        ->where('parent_id', $provinceData->id ?? 0)
        ->first();
    
    // 获取查询参数
    $level = request('level'); // city 表示市级群组
    $district = request('district'); // 县区名称
    
    // 获取群组数据
    $groups = [];
    if ($cityData) {
        $query = DB::table('wechat_groups')
            ->where('city_id', $cityData->id)
            ->where('is_active', true);
        
        if ($level === 'city') {
            // 市级群组：district_id 为 NULL
            $query->whereNull('district_id');
        } elseif ($district) {
            // 县区级群组：根据县区名称查找
            $districtData = DB::table('regions')
                ->where('name', $district . '区')
                ->orWhere('name', $district . '县')
                ->orWhere('name', $district)
                ->where('parent_id', $cityData->id)
                ->first();
            
            if ($districtData) {
                $query->where('district_id', $districtData->id);
            }
        }
        // 如果没有特殊参数，显示所有群组
        
        $groups = $query->select([
                'id',
                'group_name',
                'nickname',
                'tags',
                'current_members',
                'capacity',
                'price',
                'qr_code_logo',
                'group_logo',
                'description',
                'is_hot'
            ])
            ->get()
            ->toArray();
    }
    
    return Inertia::render('city-groups', [
        'province' => $province,
        'city' => $city,
        'groups' => $groups,
        'level' => $level,
        'district' => $district
    ]);
})->name('city-groups');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/wechat_pay.php';
