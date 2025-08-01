import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface ServiceItem {
  id: number;
  title: string;
  description: string;
  icon: string;
  features: string[];
}

export default function CustomerService() {
  const [activeTab, setActiveTab] = useState('service');

  const services: ServiceItem[] = [
    {
      id: 1,
      title: '群组推荐服务',
      description: '根据您的需求和地区，为您推荐最适合的交友群组',
      icon: '🎯',
      features: ['精准匹配', '实时推荐', '个性化定制', '7x24小时服务']
    },
    {
      id: 2,
      title: '入群指导服务',
      description: '专业客服协助您快速融入群组，掌握交友技巧',
      icon: '💬',
      features: ['入群指导', '交友技巧', '话题推荐', '社交礼仪']
    },
    {
      id: 3,
      title: '问题解答服务',
      description: '解答您在使用过程中遇到的任何问题和疑虑',
      icon: '❓',
      features: ['技术支持', '使用指导', '问题解答', '投诉建议']
    },
    {
      id: 4,
      title: '活动组织服务',
      description: '定期组织线上线下交友活动，增加交友机会',
      icon: '🎉',
      features: ['线上活动', '线下聚会', '主题派对', '兴趣匹配']
    }
  ];

  const customerServiceStaff = [
    {
      id: 1,
      name: '小美客服',
      title: '首席客服专员',
      avatar: 'https://images.unsplash.com/photo-1494790108755-2616b66e3971?w=200&h=200&fit=crop&crop=face',
      wechatQR: '/api/placeholder/200/200',
      workTime: '9:00-21:00',
      specialty: '群组推荐、入群指导'
    },
    {
      id: 2,
      name: '小帅客服',
      title: '高级客服专员',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face',
      wechatQR: '/api/placeholder/200/200',
      workTime: '9:00-21:00',
      specialty: '问题解答、技术支持'
    }
  ];

  const handleTabClick = (tab: string) => {
    setActiveTab(tab);
    if (tab === 'wechat') {
      window.location.href = '/home';
    } else if (tab === 'members') {
      window.location.href = '/members';
    } else if (tab === 'profile') {
      window.location.href = '/profile';
    }
  };

  return (
    <>
      <Head title="人工客服 - 全国同城高质量交友群" />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Hero Section */}
        <div className="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 text-center">
          <h2 className="text-2xl font-bold mb-2">专业客服团队</h2>
          <p className="text-sm opacity-90">7x24小时为您提供贴心服务</p>
        </div>

        {/* Services Section */}
        <div className="p-4">
          <h3 className="text-xl font-bold text-gray-800 mb-4">我们的服务</h3>
          <div className="space-y-4">
            {services.map((service) => (
              <Card key={service.id} className="border-0 shadow-lg">
                <CardContent className="p-4">
                  <div className="flex items-start gap-3">
                    <div className="text-3xl">{service.icon}</div>
                    <div className="flex-1">
                      <h4 className="font-semibold text-gray-800 mb-2">{service.title}</h4>
                      <p className="text-sm text-gray-600 mb-3">{service.description}</p>
                      <div className="flex flex-wrap gap-1">
                        {service.features.map((feature, index) => (
                          <Badge key={index} variant="secondary" className="text-xs">
                            {feature}
                          </Badge>
                        ))}
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>

        {/* Customer Service Staff */}
        <div className="p-4 bg-gray-50">
          <h3 className="text-xl font-bold text-gray-800 mb-4 text-center">客服团队</h3>
          <div className="space-y-4">
            {customerServiceStaff.map((staff) => (
              <Card key={staff.id} className="border-0 shadow-lg">
                <CardContent className="p-4">
                  <div className="flex items-start gap-4">
                    <img
                      src={staff.avatar}
                      alt={staff.name}
                      className="w-16 h-16 rounded-full object-cover"
                    />
                    <div className="flex-1">
                      <h4 className="font-semibold text-gray-800">{staff.name}</h4>
                      <p className="text-sm text-blue-600 mb-1">{staff.title}</p>
                      <p className="text-xs text-gray-500 mb-2">工作时间：{staff.workTime}</p>
                      <p className="text-xs text-gray-600">专长：{staff.specialty}</p>
                    </div>
                    <div className="text-center">
                      <img
                        src={staff.wechatQR}
                        alt="微信二维码"
                        className="w-20 h-20 rounded-lg border-2 border-green-500"
                      />
                      <p className="text-xs text-green-600 mt-1 font-medium">扫码添加</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>

        {/* Contact Info */}
        <div className="p-4 bg-blue-50 border-t-4 border-blue-400">
          <div className="text-center">
            <h4 className="font-semibold text-gray-800 mb-2">联系我们</h4>
            <p className="text-sm text-gray-600 mb-2">工作时间：9:00-21:00 (全年无休)</p>
            <p className="text-sm text-gray-600">响应时间：平均 &lt; 5分钟</p>
          </div>
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