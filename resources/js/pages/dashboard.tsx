import React, { useState, useEffect, useRef } from 'react';
import { Head } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import MobileTabNavigation from '@/components/mobile-tab-navigation';

interface Group {
  id: number;
  name: string;
  nickname: string;
  thumbnail: string;
  rank?: number;
  city: string;
  city_full?: string;
  province: string;
  memberCount: number;
  capacity: number;
  description: string;
  tags: string[];
  is_hot: boolean;
}

interface Province {
  id: number;
  name: string;
  shortName: string;
  color: string;
  cities: string[];
}

interface City {
  id: number;
  name: string;
  full_name: string;
  code: string;
  has_districts: boolean;
  icon: string;
}

interface District {
  id: number;
  name: string;
  full_name: string;
  code: string;
}

interface CityDistrictOption {
  id: number;
  name: string;
  full_name: string;
  code: string;
  type: 'city' | 'district';
}


interface HotCity {
  id: number;
  name: string;
  full_name: string;
  parent_id: number;
  has_districts: boolean;
}

interface DashboardProps {
  provinces: Province[];
  hotGroups: Group[];
  hotCities: HotCity[];
}

export default function Dashboard({ provinces: serverProvinces, hotGroups: serverGroups, hotCities: serverHotCities }: DashboardProps) {
  // 直辖市列表
  const directMunicipalities = ['北京市', '上海市', '天津市', '重庆市'];
  
  // 获取正确的显示名称
  const getDisplayName = (province: string, city: string, type: 'city' | 'district' = 'city') => {
    if (directMunicipalities.includes(province)) {
      return city; // 直辖市只显示城市名
    }
    return `${province}${city}`; // 其他省份显示省+市
  };
  const [selectedProvince, setSelectedProvince] = useState<Province | null>(null);
  const [selectedCity, setSelectedCity] = useState<City | null>(null);
  const [cities, setCities] = useState<City[]>([]);
  const [districts, setDistricts] = useState<District[]>([]);
  const [filteredGroups, setFilteredGroups] = useState<Group[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedRegion, setSelectedRegion] = useState<string>('全部');
  const [contentType, setContentType] = useState<'groups' | 'cities' | 'districts' | 'search' | 'city-districts' | 'default-cities'>('default-cities');
  const [searchResults, setSearchResults] = useState<{
    id: number;
    name: string;
    full_name: string;
    type: 'city' | 'district';
    city: string;
    province: string;
    icon: string;
  }[]>([]);
  const [isSearching, setIsSearching] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [cityDistrictOptions, setCityDistrictOptions] = useState<CityDistrictOption[]>([]);
  const [hotCityName, setHotCityName] = useState<string>('');
  const [defaultCities, setDefaultCities] = useState<HotCity[]>([]);
  const scrollRef = useRef<HTMLDivElement>(null);

  // 使用服务器传递的数据
  const allGroups: Group[] = serverGroups;
  const provinces: Province[] = serverProvinces;
  const hotCities: HotCity[] = serverHotCities;

  useEffect(() => {
    setFilteredGroups(allGroups);
    // 设置默认显示的城市列表：热门城市或随机15个城市
    const citiesForDefault = hotCities.length > 0 ? hotCities : 
      [...hotCities].sort(() => Math.random() - 0.5).slice(0, 15);
    setDefaultCities(citiesForDefault);
  }, []);

  useEffect(() => {
    if (selectedRegion === '全部') {
      setFilteredGroups(allGroups);
    } else {
      setFilteredGroups(allGroups.filter(group => group.city === selectedRegion));
    }
  }, [selectedRegion]);

  // 获取省份的城市列表
  const fetchCities = async (provinceId: number) => {
    setIsLoading(true);
    try {
      const response = await fetch(`/api/provinces/${provinceId}/cities`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      setCities(data.cities || []);
    } catch (error) {
      console.error('获取城市列表失败:', error);
      setCities([]);
    } finally {
      setIsLoading(false);
    }
  };


  const handleProvinceClick = (province: Province) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(15); // 省份选择震动
    }
    
    setSelectedProvince(province);
    setSelectedCity(null);
    setContentType('cities');
    setSearchQuery('');
    setCities([]);
    setDistricts([]);
    
    // 获取该省份的城市列表
    fetchCities(province.id);
  };

  const handleCityClick = async (city: City) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(20); // 城市选择稍长震动
    }
    
    setSelectedCity(city);
    
    if (city.has_districts) {
      // 有县区数据，获取并显示县区列表
      setIsLoading(true);
      try {
        const response = await fetch(`/api/cities/${city.id}/districts`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        setDistricts(data.districts || []);
        setContentType('districts');
      } catch (error) {
        console.error('获取县区列表失败:', error);
        setDistricts([]);
        setContentType('districts');
      } finally {
        setIsLoading(false);
      }
    } else {
      // 没有县区数据，直接跳转到群组页面
      if (selectedProvince) {
        window.location.href = `/groups/${selectedProvince.name}/${city.name}`;
      }
    }
  };

  const handleDistrictClick = (district: District) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(25); // 县区选择震动
    }
    
    // 跳转到群组页面
    if (selectedProvince && selectedCity) {
      // 判断是否是市级选项（id 等于 selectedCity.id）
      if (district.id === selectedCity.id) {
        // 市级群组
        window.location.href = `/groups/${selectedProvince.name}/${selectedCity.name}?level=city`;
      } else {
        // 县区级群组
        window.location.href = `/groups/${selectedProvince.name}/${selectedCity.name}?district=${district.name}`;
      }
    }
  };


  const handleRegionClick = (region: string) => {
    setSelectedRegion(region);
    setContentType('groups');
    setSelectedProvince(null);
    setSearchQuery('');
  };

  const handleHotCityClick = async (hotCity: HotCity) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(20);
    }
    
    // 获取该城市所属的省份信息
    const province = provinces.find(p => p.id === hotCity.parent_id);
    if (!province) return;
    
    setSelectedProvince(province);
    setSelectedCity({
      id: hotCity.id,
      name: hotCity.name,
      full_name: hotCity.full_name,
      code: '', // 热门城市暂时不需要code
      has_districts: hotCity.has_districts,
      icon: '' // 添加默认图标
    });
    
    // 获取城市的市级和县区级选项
    setIsLoading(true);
    try {
      const response = await fetch(`/api/cities/${hotCity.id}/city-districts`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      setCityDistrictOptions(data.options || []);
      setHotCityName(data.city_name || hotCity.name);
      setContentType('city-districts');
    } catch (error) {
      console.error('获取城市选项失败:', error);
      setCityDistrictOptions([]);
      setContentType('city-districts');
    } finally {
      setIsLoading(false);
    }
  };

  const handleCityDistrictOptionClick = (option: CityDistrictOption) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(25);
    }
    
    if (option.type === 'city') {
      // 市级群组：跳转到市级群组页面
      if (selectedProvince) {
        window.location.href = `/groups/${selectedProvince.name}/${option.name}?level=city`;
      }
    } else {
      // 县区级群组：跳转到县区级群组页面
      if (selectedProvince && selectedCity) {
        window.location.href = `/groups/${selectedProvince.name}/${selectedCity.name}?district=${option.name}`;
      }
    }
  };

  const handleSearchSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      setIsSearching(true);
      setContentType('search');
      setSelectedProvince(null);
      setSelectedRegion('全部');
      
      try {
        const response = await fetch(`/search?q=${encodeURIComponent(searchQuery)}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        setSearchResults(data.results || []);
      } catch (error) {
        console.error('搜索失败:', error);
        setSearchResults([]);
      } finally {
        setIsSearching(false);
      }
    }
  };

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearchQuery(e.target.value);
    if (e.target.value === '') {
      setContentType('groups');
      setSelectedProvince(null);
      setSearchResults([]);
    }
  };

  const handleSearchResultClick = (result: {
    id: number;
    name: string;
    full_name: string;
    type: 'city' | 'district';
    city: string;
    province: string;
    icon: string;
  }) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(20);
    }
    
    if (result.type === 'city') {
      // 城市结果：跳转到城市群组页面
      window.location.href = `/groups/${result.province}/${result.city}`;
    } else if (result.type === 'district') {
      // 县区结果：跳转到县区群组页面
      window.location.href = `/groups/${result.province}/${result.city}?district=${result.name}`;
    }
  };


  return (
    <>
      <Head title="全国同城高质量交友群" />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Hero Banner */}
        <div className="relative h-32 bg-cover bg-center flex items-center justify-center" style={{backgroundImage: "linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=800&h=400&fit=crop&crop=center')"}}>
          <div className="text-center text-white">
            <h2 className="text-2xl font-bold mb-2">同城交友搭子群</h2>
            <p className="text-sm opacity-90">全国小美小帅恋爱交友聊天搭子聚集地</p>
          </div>
          <button className="absolute top-4 right-4 bg-yellow-400 text-white px-3 py-1 rounded-full text-sm font-semibold hover:bg-yellow-500 transition-colors">
            收藏
          </button>
        </div>

        {/* Marquee Announcement */}
        <div className="bg-gray-50 border-b">
          <div className="flex items-center py-2">
            <span className="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded ml-4 flex-shrink-0">公告</span>
            <div className="flex-1 overflow-hidden ml-2">
              <div className="whitespace-nowrap animate-marquee text-red-500 font-semibold text-sm">
                全国小美小帅恋爱交友聊天搭子聚集地，恋爱搭子群，快来找属于你的搭子对象吧！🎉 诚信交友，拒绝套路！💕 优质群友等你来！
              </div>
            </div>
          </div>
        </div>

        {/* Search */}
        <div className="p-4 bg-gray-50">
          <form onSubmit={handleSearchSubmit} className="relative">
            <svg className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 0 02222">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <Input
              className="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="搜索群组或地区"
              value={searchQuery}
              onChange={handleSearchChange}
            />
          </form>
        </div>

        {/* Provinces Horizontal Scroll */}
        <div className="px-4 py-4">
          <div 
            ref={scrollRef}
            className="flex gap-4 overflow-x-auto scrollbar-hide pb-2"
            style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
          >
            {provinces.map((province) => (
              <div 
                key={province.id} 
                className="flex-shrink-0 cursor-pointer text-center"
                onClick={() => handleProvinceClick(province)}
                onTouchStart={(e) => {
                  const element = e.currentTarget.querySelector('.province-circle');
                  if (element) {
                    element.classList.add('scale-95', 'shadow-lg');
                  }
                }}
                onTouchEnd={(e) => {
                  setTimeout(() => {
                    const element = e.currentTarget.querySelector('.province-circle');
                    if (element) {
                      element.classList.remove('scale-95', 'shadow-lg');
                    }
                  }, 100);
                }}
              >
                <div className={`province-circle w-12 h-12 mx-auto ${province.color} rounded-full flex items-center justify-center text-white font-bold text-sm hover:scale-105 transition-all duration-200 transform active:scale-95 ${selectedProvince?.name === province.name ? 'ring-2 ring-blue-500 shadow-lg' : ''}`}>
                  {province.shortName}
                </div>
                <span className="text-sm mt-1 block transition-colors duration-200">{province.name}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Hot Groups */}
        <div className="px-4 py-2 bg-gray-50 border-b">
          <div className="flex items-center gap-2 overflow-x-auto scrollbar-hide" style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}>
            <span className="font-semibold flex-shrink-0">热门</span>
            {/* <button
              onClick={() => handleRegionClick('全部')}
              className={`flex-shrink-0 px-3 py-1 rounded-full text-sm whitespace-nowrap transition-colors ${selectedRegion === '全部' && contentType === 'groups' ? 'bg-blue-500 text-white' : 'bg-white text-gray-500 hover:text-blue-500'}`}
            >
              全部
            </button> */}
            {hotCities.map((city) => (
              <button
                key={city.id}
                onClick={() => handleHotCityClick(city)}
                className="flex-shrink-0 px-3 py-1 bg-white text-gray-500 hover:text-blue-500 transition-colors rounded-full text-sm whitespace-nowrap"
              >
                {city.name}
              </button>
            ))}
          </div>
        </div>

        {/* Content Display */}
        <div className="p-2 space-y-2 pb-20">
          {/* Back Button
          {(contentType === 'cities' || contentType === 'districts' || contentType === 'city-districts') && (
            <button
              onClick={handleBackClick}
              className="flex items-center text-blue-600 hover:text-blue-800 transition-colors mb-4"
            >
              <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
              </svg>
              返回
            </button>
          )} */}

          {/* Status Display
          {contentType === 'cities' && selectedProvince && (
            <div className="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
              <span className="text-blue-700 font-medium">
                {selectedProvince.name}省 ({isLoading ? '加载中...' : `${cities.length}个城市`})
              </span>
            </div>
          )} */}

          {contentType === 'districts' && selectedCity && selectedProvince && (
            <div className="bg-green-50 border-l-4 border-green-400 p-3 mb-4">
              <span className="text-green-700 font-medium">
                {selectedProvince.name} - {selectedCity.name} ({isLoading ? '加载中...' : `${districts.length}个县区`})
              </span>
            </div>
          )}

          {contentType === 'city-districts' && selectedProvince && (
            <div className="bg-orange-50 border-l-4 border-orange-400 p-3 mb-4">
              <span className="text-orange-700 font-medium">
                {selectedProvince.name} - {hotCityName} ({isLoading ? '加载中...' : `${cityDistrictOptions.length}个区域`})
              </span>
            </div>
          )}


          {contentType === 'search' && (
            <div className="bg-green-50 border-l-4 border-green-400 p-3 mb-4">
              <span className="text-green-700 font-medium">
                搜索结果："{searchQuery}" ({isSearching ? '搜索中...' : `${searchResults.length}个地区`})
              </span>
            </div>
          )}

          {contentType === 'groups' && selectedRegion !== '全部' && (
            <div className="bg-purple-50 border-l-4 border-purple-400 p-3 mb-4">
              <span className="text-purple-700 font-medium">
                当前筛选：{selectedRegion} ({filteredGroups.length}个群)
              </span>
            </div>
          )}

          {/* Cities List */}
          {contentType === 'cities' && selectedProvince && (
            <>
              
              {isLoading ? (
                <div className="text-center py-8">
                  <div className="text-gray-500">加载中...</div>
                </div>
              ) : (
                cities.map((city) => (
                  <Card key={city.id} className="border-0 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <CardContent className="p-2">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                          <div className="relative w-16 h-16 overflow-hidden">
                            <img
                              src={city.icon}
                              alt={city.name}
                              className="w-full h-full object-cover"
                              onError={(e) => {
                                const target = e.target as HTMLImageElement;
                                target.style.display = 'none';
                                const parent = target.parentElement;
                                if (parent) {
                                  parent.innerHTML = `
                                    <div class="w-12 h-12 ${selectedProvince.color} flex items-center justify-center text-white font-bold text-sm">
                                      ${city.name.charAt(0)}
                                    </div>
                                    
                                  `;
                                }
                              }}
                            />
                            {/* 红点 */}
                            
                          </div>
                          <div>
                            <h4 className="font-medium text-gray-900">{getDisplayName(selectedProvince.name, city.name)}同城交友群</h4>
                            
                            {/* <div className="flex items-center mt-1">
                              <Badge variant="outline" className="text-xs">
                                {city.has_districts ? '选择县区' : '进群'}
                              </Badge>
                            </div> */}
                          </div>
                        </div>
                        <button
                          onClick={() => handleCityClick(city)}
                          className={`${city.has_districts ? 'bg-blue-500 hover:bg-blue-600' : 'bg-green-500 hover:bg-green-600'} text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-all duration-200 transform active:scale-95 hover:scale-105 active:shadow-lg`}
                          onTouchStart={(e) => {
                            e.currentTarget.classList.add('scale-95', 'shadow-lg');
                          }}
                          onTouchEnd={(e) => {
                            setTimeout(() => {
                              e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                            }, 100);
                          }}
                        >
                          {city.has_districts ? '进群' : '进群'}
                        </button>
                      </div>
                    </CardContent>
                  </Card>
                ))
              )}
            </>
          )}

          {/* Districts List */}
          {contentType === 'districts' && selectedCity && selectedProvince && (
            <>
              <h3 className="text-lg font-semibold mb-4 text-gray-800">
                {selectedCity.name}市县区列表
              </h3>
              {isLoading ? (
                <div className="text-center py-8">
                  <div className="text-gray-500">加载中...</div>
                </div>
              ) : (
                districts.map((district) => (
                  <Card key={district.id} className="border-0 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <CardContent className="p-2">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                          <div className={`w-12 h-12 ${district.id === selectedCity.id ? 'bg-blue-500' : selectedProvince.color} rounded-full flex items-center justify-center text-white font-bold text-sm`}>
                            {district.name.charAt(0)}
                          </div>
                          <div>
                            <h4 className="font-medium text-gray-900">
                              {district.name}{district.id === selectedCity.id ? '' : ''}同城交友群
                            </h4>
                            {/* <p className="text-sm text-gray-500">
                              {district.id === selectedCity.id ? '市级群组' : `${selectedCity.name}${district.name}同城交友搭子群`}
                            </p> */}
                            {/* <div className="flex items-center mt-1">
                              <Badge variant="outline" className="text-xs">
                                {district.id === selectedCity.id ? '市级群组' : '县区群组'}
                              </Badge>
                            </div> */}
                          </div>
                        </div>
                        <button
                          onClick={() => handleDistrictClick(district)}
                          className={`${district.id === selectedCity.id ? 'bg-blue-500 hover:bg-blue-600' : 'bg-green-500 hover:bg-green-600'} text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-all duration-200 transform active:scale-95 hover:scale-105 active:shadow-lg`}
                          onTouchStart={(e) => {
                            e.currentTarget.classList.add('scale-95', 'shadow-lg');
                          }}
                          onTouchEnd={(e) => {
                            setTimeout(() => {
                              e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                            }, 100);
                          }}
                        >
                          进群
                        </button>
                      </div>
                    </CardContent>
                  </Card>
                ))
              )}
              {!isLoading && districts.length === 0 && (
                <div className="text-center py-8">
                  <div className="text-gray-500">暂无县区数据</div>
                </div>
              )}
            </>
          )}

          {/* City-Districts Options List */}
          {contentType === 'city-districts' && selectedProvince && (
            <>
              
              {isLoading ? (
                <div className="text-center py-8">
                  <div className="text-gray-500">加载中...</div>
                </div>
              ) : (
                cityDistrictOptions.map((option) => (
                  <Card key={`${option.type}-${option.id}`} className="border-0 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                    <CardContent className="p-2">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                          <div className={`w-12 h-12 ${option.type === 'city' ? 'bg-blue-500' : selectedProvince.color} rounded-full flex items-center justify-center text-white font-bold text-sm`}>
                            {option.name.charAt(0)}
                          </div>
                          <div>
                            <h4 className="font-medium text-gray-900">
                              {option.name}{option.type === 'city' ? '' : ''}同城交友群
                            </h4>
                            {/* <p className="text-sm text-gray-500">
                              {option.type === 'city' ? '市级群组' : '县区级群组'}
                            </p> */}
                            {/* <div className="flex items-center mt-1">
                              <Badge variant="outline" className="text-xs">
                                {option.type === 'city' ? '市级群组' : '县区群组'}
                              </Badge>
                            </div> */}
                          </div>
                        </div>
                        <button
                          onClick={() => handleCityDistrictOptionClick(option)}
                          className={`${option.type === 'city' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-green-500 hover:bg-green-600'} text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-all duration-200 transform active:scale-95 hover:scale-105 active:shadow-lg`}
                          onTouchStart={(e) => {
                            e.currentTarget.classList.add('scale-95', 'shadow-lg');
                          }}
                          onTouchEnd={(e) => {
                            setTimeout(() => {
                              e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                            }, 100);
                          }}
                        >
                          进群
                        </button>
                      </div>
                    </CardContent>
                  </Card>
                ))
              )}
              {!isLoading && cityDistrictOptions.length === 0 && (
                <div className="text-center py-8">
                  <div className="text-gray-500">暂无群组数据</div>
                </div>
              )}
            </>
          )}

          {/* Search Results */}
          {contentType === 'search' && (
            <>
              <h3 className="text-lg font-semibold mb-4 text-gray-800">
                搜索结果
              </h3>
              {isSearching ? (
                <div className="text-center py-8">
                  <div className="text-gray-500">搜索中...</div>
                </div>
              ) : (
                searchResults.map((result) => (
                  <div key={`${result.type}-${result.id}`} className="flex items-center justify-between bg-white rounded-lg shadow-sm p-2 border border-gray-100">
                    <div className="flex items-center">
                      <div className="relative w-22 h-22">
                        <img 
                          alt={result.name}
                          className="w-full h-full object-cover"
                          src={result.icon}
                          onError={(e) => {
                            const target = e.target as HTMLImageElement;
                            target.style.display = 'none';
                            const parent = target.parentElement;
                            if (parent) {
                              parent.innerHTML = `
                                <div class="w-16 h-16 bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                  ${result.name.charAt(0)}
                                </div>
                              `;
                            }
                          }}
                        />
                        {/* 红点 */}
                        
                      </div>
                      <div className="ml-2 flex-1">
                        <h3 className="font-semibold text-gray-800">
                          {directMunicipalities.includes(result.province) ? result.name : `${result.province}${result.name}`}{result.type === 'city' ? '' : ''}同城交友群
                        </h3>
                        <p className="text-xs text-gray-500 mt-1">
                          {result.type === 'city' ? '市级群组' : '县区级群组'}
                        </p>
                        <div className="flex items-center mt-2">
                          <span className="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                            {getDisplayName(result.province, result.city)}
                          </span>
                          <span className="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full ml-2">
                            {result.type === 'city' ? '市级' : '县区'}
                          </span>
                        </div>
                      </div>
                    </div>
                    <button 
                      onClick={() => handleSearchResultClick(result)}
                      className="border border-green-500 text-green-500 px-4 py-1 rounded-full text-sm whitespace-nowrap hover:bg-green-50 transition-colors"
                    >
                      进群
                    </button>
                  </div>
                ))
              )}
              {!isSearching && searchResults.length === 0 && searchQuery && (
                <div className="text-center py-8">
                  <div className="text-gray-500">没有找到相关地区</div>
                </div>
              )}
            </>
          )}

          {/* Default Cities List */}
          {contentType === 'default-cities' && (
            <>
              {defaultCities.map((city) => (
                <Card key={city.id} className="border-0 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                  <CardContent className="p-1">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center space-x-2">
                        <div className="relative w-16 h-16 overflow-hidden">
                          <img
                            src={`/images/groups/qun${city.id % 10}.jpg`}
                            alt={city.name}
                            className="w-full h-full object-cover"
                            onError={(e) => {
                              const target = e.target as HTMLImageElement;
                              target.style.display = 'none';
                              const parent = target.parentElement;
                              if (parent) {
                                parent.innerHTML = `
                                  <div class="w-16 h-16 ${provinces.find(p => p.id === city.parent_id)?.color || 'bg-blue-500'} flex items-center justify-center text-white font-bold text-sm">
                                    ${city.name.charAt(0)}
                                  </div>
                                  
                                `;
                              }
                            }}
                          />
                          {/* 红点 */}
                          
                        </div>
                        <div>
                          <h4 className="font-medium text-gray-900">{city.name}同城交友群</h4>
                          <p className="text-sm text-gray-500 mt-1">
                            {provinces.find(p => p.id === city.parent_id)?.name || ''}
                          </p>
                        </div>
                      </div>
                      <button
                        onClick={() => handleHotCityClick(city)}
                        className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-all duration-200 transform active:scale-95 hover:scale-105 active:shadow-lg"
                        onTouchStart={(e) => {
                          e.currentTarget.classList.add('scale-95', 'shadow-lg');
                        }}
                        onTouchEnd={(e) => {
                          setTimeout(() => {
                            e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                          }, 100);
                        }}
                      >
                        进群
                      </button>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </>
          )}

          {/* Default Groups List */}
          {contentType === 'groups' && (
            <>
              {filteredGroups.map((group, index) => (
                <div key={group.id} className="flex items-center justify-between bg-white rounded-lg shadow-sm p-2 border border-gray-100">
                  <div className="flex items-center">
                    <div className="relative w-22 h-22">
                      <img 
                        alt={group.name}
                        className="w-full h-full object-cover"
                        src={group.thumbnail}
                        onError={(e) => {
                          const target = e.target as HTMLImageElement;
                          target.src = `/images/groups/qun${group.id % 10}.jpg`;
                        }}
                      />
                      {group.is_hot ? (
                        <span className="absolute -top-1 -left-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                          热
                        </span>
                      ) : (
                        <span className="absolute -top-1 -left-1 bg-blue-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                          {index + 1}
                        </span>
                      )}
                    </div>
                    <div className="ml-2 flex-1">
                      <h3 className="font-semibold text-gray-800">{group.name}</h3>
                      <p className="text-xs text-gray-500 mt-1">{group.description}</p>
                      <div className="flex items-center mt-2">
                        <span className="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">{group.city}</span>
                        <span className="text-xs text-gray-500 ml-2">{group.memberCount}/{group.capacity}人</span>
                        {group.tags && group.tags.length > 0 && (
                          <span className="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full ml-2">
                            {group.tags[0]}
                          </span>
                        )}
                      </div>
                    </div>
                  </div>
                  <button 
                    onClick={() => window.location.href = `/groups/${group.province}/${group.city}`}
                    className="border border-green-500 text-green-500 px-4 py-1 rounded-full text-sm whitespace-nowrap hover:bg-green-50 transition-colors"
                  >
                    进群
                  </button>
                </div>
              ))}
            </>
          )}
        </div>
      </div>
      
      {/* CSS for marquee animation */}
      <style>{`
        @keyframes marquee {
          0% { transform: translateX(100%); }
          100% { transform: translateX(-100%); }
        }
        .animate-marquee {
          animation: marquee 15s linear infinite;
        }
        .scrollbar-hide {
          -ms-overflow-style: none;
          scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
          display: none;
        }
      `}</style>
      
      <MobileTabNavigation />
    </>
  );
}