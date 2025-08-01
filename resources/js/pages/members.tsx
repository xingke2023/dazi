import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Member {
  id: number;
  name: string;
  avatar: string;
  age: number;
  city: string;
  profession: string;
  hobbies: string[];
  description: string;
  lookingFor: string;
  education: string;
  height: string;
  isOnline: boolean;
}

export default function Members() {
  const [members, setMembers] = useState<Member[]>([]);
  const [activeTab, setActiveTab] = useState('members');
  const [selectedCity, setSelectedCity] = useState<string>('全部');

  const allMembers: Member[] = [
    {
      id: 1,
      name: '小美',
      avatar: 'https://images.unsplash.com/photo-1494790108755-2616b66e3971?w=200&h=200&fit=crop&crop=face',
      age: 25,
      city: '北京',
      profession: '产品经理',
      hobbies: ['旅行', '摄影', '美食', '读书'],
      description: '喜欢探索新事物，热爱生活的阳光女孩，希望找到志同道合的朋友一起分享生活的美好。',
      lookingFor: '寻找真诚、有趣的灵魂伴侣',
      education: '本科',
      height: '165cm',
      isOnline: true
    },
    {
      id: 2,
      name: '小帅',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face',
      age: 28,
      city: '上海',
      profession: '软件工程师',
      hobbies: ['健身', '电影', '游戏', '篮球'],
      description: '喜欢运动和科技，性格开朗幽默，相信缘分，希望能遇到那个对的人。',
      lookingFor: '寻找温柔善良的另一半',
      education: '硕士',
      height: '178cm',
      isOnline: true
    },
    {
      id: 3,
      name: '晓雯',
      avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&h=200&fit=crop&crop=face',
      age: 26,
      city: '广州',
      profession: '设计师',
      hobbies: ['绘画', '音乐', '咖啡', '瑜伽'],
      description: '文艺女青年，喜欢一切美好的事物，享受慢生活，希望找到一个懂得欣赏生活的人。',
      lookingFor: '寻找有共同兴趣的文艺伴侣',
      education: '本科',
      height: '162cm',
      isOnline: false
    },
    {
      id: 4,
      name: '志明',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face',
      age: 30,
      city: '深圳',
      profession: '金融分析师',
      hobbies: ['投资', '读书', '跑步', '旅行'],
      description: '成熟稳重，有责任心，喜欢思考和学习，希望找到一个可以共同成长的伴侣。',
      lookingFor: '寻找成熟理智的人生伴侣',
      education: '硕士',
      height: '175cm',
      isOnline: true
    },
    {
      id: 5,
      name: '诗涵',
      avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop&crop=face',
      age: 24,
      city: '杭州',
      profession: '教师',
      hobbies: ['教育', '书法', '古典音乐', '园艺'],
      description: '温柔细腻，喜欢传统文化，热爱教育事业，希望找到一个有涵养的人共度人生。',
      lookingFor: '寻找有文化底蕴的知心人',
      education: '本科',
      height: '160cm',
      isOnline: true
    },
    {
      id: 6,
      name: '建华',
      avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop&crop=face',
      age: 27,
      city: '成都',
      profession: '创业者',
      hobbies: ['创业', '美食', '摄影', '户外'],
      description: '热爱挑战，积极向上，喜欢尝试新事物，希望找到一个能够支持我梦想的人。',
      lookingFor: '寻找理解支持的人生伙伴',
      education: '本科',
      height: '172cm',
      isOnline: false
    },
    {
      id: 7,
      name: '雨薇',
      avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&h=200&fit=crop&crop=face',
      age: 23,
      city: '西安',
      profession: '护士',
      hobbies: ['医疗', '烘焙', '电影', '宠物'],
      description: '善良有爱心，喜欢帮助别人，生活温馨，希望找到一个同样善良的人。',
      lookingFor: '寻找善良温暖的另一半',
      education: '专科',
      height: '158cm',
      isOnline: true
    },
    {
      id: 8,
      name: '俊杰',
      avatar: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=200&h=200&fit=crop&crop=face',
      age: 29,
      city: '重庆',
      profession: '医生',
      hobbies: ['医学', '登山', '古典音乐', '收藏'],
      description: '专业认真，生活充实，喜欢安静的生活，希望找到一个能够理解我工作的人。',
      lookingFor: '寻找理解包容的终身伴侣',
      education: '博士',
      height: '180cm',
      isOnline: true
    }
  ];

  const cities = ['全部', '北京', '上海', '广州', '深圳', '杭州', '成都', '西安', '重庆'];

  useEffect(() => {
    setMembers(allMembers);
  }, []);

  const filteredMembers = selectedCity === '全部' 
    ? members 
    : members.filter(member => member.city === selectedCity);

  const handleTabClick = (tab: string) => {
    setActiveTab(tab);
    if (tab === 'wechat') {
      window.location.href = '/home';
    } else if (tab === 'service') {
      window.location.href = '/service';
    } else if (tab === 'profile') {
      window.location.href = '/profile';
    }
  };

  return (
    <>
      <Head title="群友展示 - 全国同城高质量交友群" />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Header */}
        <header className="relative flex items-center justify-between p-4 border-b bg-cover bg-center text-white" style={{backgroundImage: "linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800&h=400&fit=crop&crop=center')"}}>
          <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
          </svg>
          <h1 className="text-lg font-semibold text-white">群友展示</h1>
          <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zM12 13a1 1 0 110-2 1 1 0 010 2zM12 20a1 1 0 110-2 1 1 0 010 2z" />
          </svg>
        </header>

        {/* Hero Section */}
        <div className="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-6 text-center">
          <h2 className="text-2xl font-bold mb-2">优质群友</h2>
          <p className="text-sm opacity-90">真实认证的高质量交友对象</p>
        </div>

        {/* City Filter */}
        <div className="p-4 bg-gray-50 border-b">
          <div className="flex gap-2 flex-wrap">
            {cities.map((city) => (
              <button
                key={city}
                onClick={() => setSelectedCity(city)}
                className={`px-3 py-1 rounded-full text-sm transition-colors ${
                  selectedCity === city 
                    ? 'bg-blue-500 text-white' 
                    : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-100'
                }`}
              >
                {city}
              </button>
            ))}
          </div>
        </div>

        {/* Members List */}
        <div className="p-4 space-y-4 pb-20">
          <div className="text-center text-gray-600 text-sm mb-4">
            共 {filteredMembers.length} 位群友
          </div>
          
          {filteredMembers.map((member) => (
            <Card key={member.id} className="border-0 shadow-lg hover:shadow-xl transition-shadow">
              <CardContent className="p-4">
                <div className="flex items-start gap-4">
                  <div className="relative">
                    <img
                      src={member.avatar}
                      alt={member.name}
                      className="w-20 h-20 rounded-full object-cover"
                    />
                    {member.isOnline && (
                      <div className="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    )}
                  </div>
                  <div className="flex-1">
                    <div className="flex items-center gap-2 mb-2">
                      <h3 className="font-semibold text-gray-800">{member.name}</h3>
                      <Badge variant="outline" className="text-xs">
                        {member.age}岁
                      </Badge>
                      <Badge variant="secondary" className="text-xs">
                        {member.city}
                      </Badge>
                    </div>
                    
                    <div className="space-y-1 mb-3">
                      <p className="text-sm text-gray-600">
                        <span className="font-medium">职业：</span>{member.profession}
                      </p>
                      <p className="text-sm text-gray-600">
                        <span className="font-medium">学历：</span>{member.education} | 
                        <span className="font-medium"> 身高：</span>{member.height}
                      </p>
                    </div>
                    
                    <p className="text-sm text-gray-700 mb-3">{member.description}</p>
                    
                    <div className="mb-3">
                      <p className="text-sm font-medium text-gray-700 mb-1">兴趣爱好：</p>
                      <div className="flex flex-wrap gap-1">
                        {member.hobbies.map((hobby, index) => (
                          <Badge key={index} variant="outline" className="text-xs">
                            {hobby}
                          </Badge>
                        ))}
                      </div>
                    </div>
                    
                    <div className="bg-pink-50 p-2 rounded-lg">
                      <p className="text-sm text-pink-700">
                        <span className="font-medium">💝 </span>{member.lookingFor}
                      </p>
                    </div>
                  </div>
                </div>
                
                <div className="mt-4 flex gap-2">
                  <button className="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                    了解更多
                  </button>
                  <button className="flex-1 border border-blue-500 text-blue-500 py-2 px-4 rounded-lg text-sm hover:bg-blue-50 transition-colors">
                    发送消息
                  </button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Bottom Navigation */}
        <footer className="fixed bottom-0 left-0 right-0 max-w-sm mx-auto bg-white border-t flex justify-around py-2">
          <button 
            onClick={() => handleTabClick('wechat')}
            className={`text-center ${activeTab === 'wechat' ? 'text-blue-500' : 'text-gray-500'}`}
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span className="block text-xs">同城微信群</span>
          </button>
          <button 
            onClick={() => handleTabClick('service')}
            className={`text-center ${activeTab === 'service' ? 'text-blue-500' : 'text-gray-500'}`}
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span className="block text-xs">人工客服</span>
          </button>
          <button 
            onClick={() => handleTabClick('members')}
            className={`text-center ${activeTab === 'members' ? 'text-blue-500' : 'text-gray-500'}`}
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span className="block text-xs">群友展示</span>
          </button>
          <button 
            onClick={() => handleTabClick('profile')}
            className={`text-center ${activeTab === 'profile' ? 'text-blue-500' : 'text-gray-500'}`}
          >
            <svg className="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span className="block text-xs">我的</span>
          </button>
        </footer>
      </div>
    </>
  );
}