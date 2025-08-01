import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
  User, 
  Share2, 
  Gift, 
  DollarSign, 
  FileText, 
  Settings, 
  LogOut,
  Copy,
  ChevronRight
} from 'lucide-react';
import MobileTabNavigation from '@/components/mobile-tab-navigation';

export default function Profile() {
  const [activeTab, setActiveTab] = useState('profile');

  const userProfile = {
    name: '张小美',
    avatar: 'https://images.unsplash.com/photo-1494790108755-2616b66e3971?w=200&h=200&fit=crop&crop=face',
    id: 'U001234',
    referralCode: 'REF123456',
    referralLink: 'https://app.example.com/invite/REF123456',
    totalReferrals: 15,
    totalEarnings: 2580.50,
    memberSince: '2024年1月',
    level: 'VIP会员'
  };

  const menuItems = [
    {
      id: 'referral-code',
      title: '我的推荐码',
      subtitle: userProfile.referralCode,
      icon: Share2,
      color: 'text-blue-600',
      bgColor: 'bg-blue-50',
      action: () => navigator.clipboard.writeText(userProfile.referralCode)
    },
    {
      id: 'referral-link',
      title: '我的推荐链接',
      subtitle: '点击复制分享链接',
      icon: Copy,
      color: 'text-green-600',
      bgColor: 'bg-green-50',
      action: () => navigator.clipboard.writeText(userProfile.referralLink)
    },
    {
      id: 'my-referrals',
      title: '我的推荐',
      subtitle: `已推荐 ${userProfile.totalReferrals} 人`,
      icon: Gift,
      color: 'text-purple-600',
      bgColor: 'bg-purple-50',
      action: () => console.log('查看推荐列表')
    },
    {
      id: 'earnings',
      title: '我的收益',
      subtitle: `¥${userProfile.totalEarnings.toFixed(2)}`,
      icon: DollarSign,
      color: 'text-orange-600',
      bgColor: 'bg-orange-50',
      action: () => console.log('查看收益详情')
    },
    {
      id: 'profile-info',
      title: '我的资料',
      subtitle: '完善个人信息',
      icon: FileText,
      color: 'text-indigo-600',
      bgColor: 'bg-indigo-50',
      action: () => console.log('编辑资料')
    },
    {
      id: 'settings',
      title: '设置',
      subtitle: '账号与隐私设置',
      icon: Settings,
      color: 'text-gray-600',
      bgColor: 'bg-gray-50',
      action: () => console.log('打开设置')
    }
  ];

  const handleLogout = () => {
    if (confirm('确定要退出登录吗？')) {
      // 这里应该调用 Laravel 的 logout 路由
      window.location.href = '/logout';
    }
  };

  return (
    <>
      <Head title="我的 - 全国同城高质量交友群" />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Header */}
        <header className="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4">
          <h1 className="text-lg font-semibold">我的</h1>
        </header>

        {/* User Profile Section */}
        <div className="p-4">
          <Card className="border-0 shadow-lg">
            <CardContent className="p-6">
              <div className="flex items-center gap-4 mb-4">
                <img
                  src={userProfile.avatar}
                  alt={userProfile.name}
                  className="w-16 h-16 rounded-full object-cover border-4 border-white shadow-lg"
                />
                <div className="flex-1">
                  <h2 className="text-xl font-bold text-gray-800">{userProfile.name}</h2>
                  <div className="flex items-center gap-2 mt-1">
                    <Badge variant="outline" className="text-xs">
                      ID: {userProfile.id}
                    </Badge>
                    <Badge variant="secondary" className="text-xs">
                      {userProfile.level}
                    </Badge>
                  </div>
                  <p className="text-sm text-gray-600 mt-1">加入时间：{userProfile.memberSince}</p>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="text-center bg-blue-50 p-3 rounded-lg">
                  <div className="text-2xl font-bold text-blue-600">{userProfile.totalReferrals}</div>
                  <div className="text-xs text-gray-600">推荐人数</div>
                </div>
                <div className="text-center bg-green-50 p-3 rounded-lg">
                  <div className="text-2xl font-bold text-green-600">¥{userProfile.totalEarnings.toFixed(0)}</div>
                  <div className="text-xs text-gray-600">总收益</div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Menu Items */}
        <div className="p-4 space-y-3">
          {menuItems.map((item) => {
            const Icon = item.icon;
            return (
              <Card 
                key={item.id} 
                className="border-0 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                onClick={item.action}
              >
                <CardContent className="p-4">
                  <div className="flex items-center gap-4">
                    <div className={`p-2 rounded-lg ${item.bgColor}`}>
                      <Icon className={`w-5 h-5 ${item.color}`} />
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold text-gray-800">{item.title}</h3>
                      <p className="text-sm text-gray-600">{item.subtitle}</p>
                    </div>
                    <ChevronRight className="w-5 h-5 text-gray-400" />
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Logout Button */}
        <div className="p-4 pb-20">
          <button 
            onClick={handleLogout}
            className="w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 transition-colors flex items-center justify-center gap-2"
          >
            <LogOut className="w-5 h-5" />
            退出登录
          </button>
        </div>
      </div>
      <MobileTabNavigation />
    </>
  );
}