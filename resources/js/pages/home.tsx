import React, { useState, useEffect, useRef } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Group {
  id: number;
  name: string;
  thumbnail: string;
  rank: number;
  city: string;
  memberCount: number;
  description: string;
}

interface Province {
  id: number;
  name: string;
  shortName: string;
  color: string;
}

export default function Home() {
  const [filteredGroups, setFilteredGroups] = useState<Group[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedRegion, setSelectedRegion] = useState<string>('全部');
  const scrollRef = useRef<HTMLDivElement>(null);


  const allGroups: Group[] = [
    { id: 1, name: '北京海淀区单身交友群', thumbnail: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=400&fit=crop', rank: 1, city: '北京', memberCount: 1258, description: '北京海淀区优质单身男女交友群' },
    { id: 2, name: '北京朝阳区恋爱搭子群', thumbnail: 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=400&h=400&fit=crop', rank: 2, city: '北京', memberCount: 986, description: '朝阳区年轻人聚集地' },
    { id: 3, name: '北京丰台区脱单群', thumbnail: 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=400&h=400&fit=crop', rank: 3, city: '北京', memberCount: 743, description: '丰台区脱单交友' },
    { id: 4, name: '上海浦东新区交友群', thumbnail: 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1f?w=400&h=400&fit=crop', rank: 4, city: '上海', memberCount: 1456, description: '浦东新区高质量交友' },
    { id: 5, name: '上海徐汇区恋爱群', thumbnail: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=400&fit=crop', rank: 5, city: '上海', memberCount: 892, description: '徐汇区文艺青年聚集地' },
    { id: 6, name: '上海静安区搭子群', thumbnail: 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=400&h=400&fit=crop', rank: 6, city: '上海', memberCount: 1124, description: '静安区都市白领交友' },
    { id: 7, name: '重庆渝中区同城群', thumbnail: 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=400&h=400&fit=crop', rank: 7, city: '重庆', memberCount: 678, description: '重庆渝中区火锅搭子' },
    { id: 8, name: '重庆南岸区交友群', thumbnail: 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1f?w=400&h=400&fit=crop', rank: 8, city: '重庆', memberCount: 534, description: '南岸区年轻人聚会' },
    { id: 9, name: '天津和平区恋爱群', thumbnail: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=400&fit=crop', rank: 9, city: '天津', memberCount: 423, description: '和平区优质交友' },
    { id: 10, name: '天津河西区搭子群', thumbnail: 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=400&h=400&fit=crop', rank: 10, city: '天津', memberCount: 367, description: '河西区聊天交友' },
    { id: 11, name: '广州天河区交友群', thumbnail: 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=400&h=400&fit=crop', rank: 11, city: '广州', memberCount: 1789, description: '天河区CBD白领交友' },
    { id: 12, name: '深圳南山区科技群', thumbnail: 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1f?w=400&h=400&fit=crop', rank: 12, city: '深圳', memberCount: 2134, description: '南山区科技精英交友' },
    { id: 13, name: '杭州西湖区文艺群', thumbnail: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=400&fit=crop', rank: 13, city: '杭州', memberCount: 856, description: '西湖边的文艺青年' },
    { id: 14, name: '成都锦江区美食群', thumbnail: 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=400&h=400&fit=crop', rank: 14, city: '成都', memberCount: 1456, description: '锦江区美食爱好者' },
    { id: 15, name: '西安雁塔区学生群', thumbnail: 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=400&h=400&fit=crop', rank: 15, city: '西安', memberCount: 789, description: '雁塔区大学生交友' },
  ];

  useEffect(() => {
    setFilteredGroups(allGroups);
  }, []);

  useEffect(() => {
    if (selectedRegion === '全部') {
      setFilteredGroups(allGroups);
    } else {
      setFilteredGroups(allGroups.filter(group => group.city === selectedRegion));
    }
  }, [selectedRegion]);

  const provinces: Province[] = [
    { id: 1, name: '河北', shortName: '冀', color: 'bg-red-500' },
    { id: 2, name: '山东', shortName: '齐', color: 'bg-red-500' },
    { id: 3, name: '辽宁', shortName: '辽', color: 'bg-red-500' },
    { id: 4, name: '甘肃', shortName: '陇', color: 'bg-red-500' },
    { id: 5, name: '吉林', shortName: '吉', color: 'bg-red-500' },
    { id: 6, name: '青海', shortName: '青', color: 'bg-blue-500' },
    { id: 7, name: '黑龙江', shortName: '黑', color: 'bg-black' },
    { id: 8, name: '河南', shortName: '豫', color: 'bg-red-500' },
    { id: 9, name: '江苏', shortName: '苏', color: 'bg-red-500' },
    { id: 10, name: '湖北', shortName: '鄂', color: 'bg-red-500' },
    { id: 11, name: '北京', shortName: '京', color: 'bg-red-500' },
    { id: 12, name: '上海', shortName: '沪', color: 'bg-red-500' },
    { id: 13, name: '天津', shortName: '津', color: 'bg-blue-500' },
    { id: 14, name: '重庆', shortName: '渝', color: 'bg-red-500' },
    { id: 15, name: '广东', shortName: '粤', color: 'bg-red-500' },
    { id: 16, name: '浙江', shortName: '浙', color: 'bg-green-500' },
    { id: 17, name: '福建', shortName: '闽', color: 'bg-red-500' },
    { id: 18, name: '四川', shortName: '川', color: 'bg-red-500' },
    { id: 19, name: '湖南', shortName: '湘', color: 'bg-red-500' },
    { id: 20, name: '安徽', shortName: '皖', color: 'bg-red-500' },
  ];

  const hotCities = ['北京', '重庆', '上海', '天津'];

  const handleRegionClick = (region: string) => {
    setSelectedRegion(region);
  };

  const handleProvinceClick = (province: string) => {
    setSelectedRegion(province);
  };


  return (
    <>
      <Head title="全国同城高质量交友群" />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Hero Banner */}
        <div className="relative h-48 bg-cover bg-center flex items-center justify-center" style={{backgroundImage: "linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=800&h=400&fit=crop&crop=center')"}}>
          <div className="text-center text-white">
            <h2 className="text-2xl font-bold mb-2">同城交友搭子群</h2>
            <p className="text-sm opacity-90">全国小美小帅恋爱交友聊天搭子聚集地</p>
          </div>
          <button className="absolute top-4 right-4 bg-yellow-400 text-white px-3 py-1 rounded-full text-sm font-semibold hover:bg-yellow-500 transition-colors">
            收藏
          </button>
        </div>

        {/* Announcement and Search */}
        <div className="p-4 bg-gray-50">
          <div className="flex items-center mb-4">
            <span className="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">公告</span>
            <p className="ml-4 text-red-500 font-semibold animate-pulse">全国小美小帅恋爱交友聊天搭子聚集地，恋爱搭子群，快来找属于你的搭子对象吧！</p>
          </div>
          <div className="relative">
            <svg className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <Input
              className="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="搜索"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
        </div>

        {/* Provinces Horizontal Scroll */}
        <div className="px-4 py-4">
          <div 
            ref={scrollRef}
            className="flex gap-6 overflow-x-auto scrollbar-hide pb-2"
            style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
          >
            {provinces.map((province) => (
              <div 
                key={province.id} 
                className="flex-shrink-0 cursor-pointer text-center"
                onClick={() => handleProvinceClick(province.name)}
              >
                <div className={`w-12 h-12 mx-auto ${province.color} rounded-full flex items-center justify-center text-white font-bold text-sm hover:scale-105 transition-transform ${selectedRegion === province.name ? 'ring-2 ring-blue-500' : ''}`}>
                  {province.shortName}
                </div>
                <span className="text-sm mt-1 block">{province.name}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Hot Groups */}
        <div className="px-4 py-2 bg-gray-50">
          <span className="font-semibold">热门群</span>
          <button
            onClick={() => handleRegionClick('全部')}
            className={`ml-4 ${selectedRegion === '全部' ? 'text-blue-500 font-semibold' : 'text-gray-500'}`}
          >
            全部
          </button>
          {hotCities.map((city) => (
            <button
              key={city}
              onClick={() => handleRegionClick(city)}
              className={`ml-4 ${selectedRegion === city ? 'text-blue-500 font-semibold' : 'text-gray-500'}`}
            >
              {city}
            </button>
          ))}
        </div>

        {/* Selected Region Display */}
        {selectedRegion !== '全部' && (
          <div className="px-4 py-2 bg-blue-50 border-l-4 border-blue-400">
            <span className="text-blue-700 font-medium">当前筛选：{selectedRegion} ({filteredGroups.length}个群)</span>
          </div>
        )}

        {/* Groups List */}
        <div className="p-4 space-y-4 pb-20">
          {filteredGroups.map((group) => (
            <div key={group.id} className="flex items-center justify-between bg-white rounded-lg shadow-sm p-3 border border-gray-100">
              <div className="flex items-center">
                <div className="relative w-16 h-16">
                  <img 
                    alt={group.name}
                    className="w-full h-full rounded-lg object-cover"
                    src={group.thumbnail}
                  />
                  <span className="absolute -top-1 -left-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                    {group.rank}
                  </span>
                </div>
                <div className="ml-4 flex-1">
                  <h3 className="font-semibold text-gray-800">{group.name}</h3>
                  <p className="text-xs text-gray-500 mt-1">{group.description}</p>
                  <div className="flex items-center mt-2">
                    <span className="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">{group.city}</span>
                    <span className="text-xs text-gray-500 ml-2">{group.memberCount}人</span>
                  </div>
                </div>
              </div>
              <button className="border border-green-500 text-green-500 px-4 py-1 rounded-full text-sm hover:bg-green-50 transition-colors">
                入群
              </button>
            </div>
          ))}
        </div>

        {/* Bottom Navigation */}
        <footer className="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white border-t flex justify-around py-2">
          <Link 
            href="/home"
            className="text-center text-blue-500"
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span className="block text-xs">同城微信群</span>
          </Link>
          <Link 
            href="/members"
            className="text-center text-gray-500"
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span className="block text-xs">群友展示</span>
          </Link>
          <Link 
            href="/service"
            className="text-center text-gray-500"
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span className="block text-xs">人工客服</span>
          </Link>
          <Link 
            href="/profile"
            className="text-center text-gray-500"
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span className="block text-xs">我的</span>
          </Link>
        </footer>
      </div>
    </>
  );
}