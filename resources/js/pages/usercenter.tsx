import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import MobileLayout from '@/layouts/mobile-layout';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { User, Image, Users, Share2, ShoppingBag, UserCheck, Copy, Eye } from 'lucide-react';

interface OrderItem {
    id: string;
    type: 'membership' | 'vip' | 'poster';
    title: string;
    amount: number;
    status: 'pending' | 'paid' | 'completed' | 'cancelled';
    date: string;
    referrer?: string;
}

export default function UserCenter() {
    const [activeTab, setActiveTab] = useState<'orders' | 'referrals'>('orders');
    const [showPromotionLink, setShowPromotionLink] = useState(false);
    
    // Mock user data
    const user = {
        name: '张三',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face',
        id: 'U123456',
        memberLevel: 'VIP',
        joinDate: '2024-01-15',
        referralCode: 'REF123456'
    };

    // Mock orders data
    const myOrders: OrderItem[] = [
        {
            id: 'O001',
            type: 'membership',
            title: 'VIP会员',
            amount: 199,
            status: 'completed',
            date: '2024-01-15 10:30',
        },
        {
            id: 'O002',
            type: 'poster',
            title: '专属海报',
            amount: 29,
            status: 'paid',
            date: '2024-01-20 14:22',
        }
    ];

    // Mock referral orders data
    const referralOrders: OrderItem[] = [
        {
            id: 'R001',
            type: 'membership',
            title: 'VIP会员',
            amount: 199,
            status: 'completed',
            date: '2024-01-18 09:15',
            referrer: '李四'
        },
        {
            id: 'R002',
            type: 'vip',
            title: '高级VIP',
            amount: 399,
            status: 'paid',
            date: '2024-01-22 16:45',
            referrer: '王五'
        }
    ];

    const getStatusColor = (status: OrderItem['status']) => {
        switch (status) {
            case 'completed': return 'bg-green-100 text-green-800';
            case 'paid': return 'bg-blue-100 text-blue-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    const getStatusText = (status: OrderItem['status']) => {
        switch (status) {
            case 'completed': return '已完成';
            case 'paid': return '已支付';
            case 'pending': return '待支付';
            case 'cancelled': return '已取消';
            default: return '未知';
        }
    };

    const promotionLink = `https://dazi.xingke888.com/register?ref=${user.referralCode}`;

    const copyToClipboard = (text: string) => {
        navigator.clipboard.writeText(text);
        alert('链接已复制到剪贴板');
    };

    return (
        <MobileLayout>
            <Head title="用户中心" />
            
            <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
                {/* User Profile Header */}
                <div className="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                    <div className="flex items-center space-x-4">
                        <div className="relative">
                            <img
                                src={user.avatar}
                                alt={user.name}
                                className="w-16 h-16 rounded-full border-4 border-white/20"
                            />
                            <div className="absolute -bottom-1 -right-1 bg-yellow-400 text-yellow-900 text-xs px-2 py-0.5 rounded-full font-medium">
                                {user.memberLevel}
                            </div>
                        </div>
                        <div className="flex-1">
                            <h1 className="text-xl font-bold">{user.name}</h1>
                            <p className="text-blue-100 text-sm">ID: {user.id}</p>
                            <p className="text-blue-100 text-sm">加入时间: {user.joinDate}</p>
                        </div>
                    </div>
                </div>

                {/* Action Buttons */}
                <div className="bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700">
                    <div className="grid grid-cols-2 gap-3">
                        <button className="flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white py-3 px-4 rounded-lg hover:from-pink-600 hover:to-rose-600 transition-all">
                            <Image className="w-5 h-5" />
                            <span className="font-medium">专属海报</span>
                        </button>
                        <button className="flex items-center justify-center space-x-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-3 px-4 rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all">
                            <Users className="w-5 h-5" />
                            <span className="font-medium">我的推荐</span>
                        </button>
                    </div>
                </div>

                {/* Promotion Link Section */}
                <div className="bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700">
                    <div className="flex items-center justify-between mb-3">
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <Share2 className="w-5 h-5 mr-2 text-blue-500" />
                            推广链接
                        </h3>
                        <button
                            onClick={() => setShowPromotionLink(!showPromotionLink)}
                            className="text-blue-500 hover:text-blue-600"
                        >
                            <Eye className="w-5 h-5" />
                        </button>
                    </div>
                    {showPromotionLink && (
                        <div className="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <div className="flex items-center justify-between">
                                <span className="text-sm text-gray-600 dark:text-gray-300 font-mono flex-1 mr-2 break-all">
                                    {promotionLink}
                                </span>
                                <button
                                    onClick={() => copyToClipboard(promotionLink)}
                                    className="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors flex items-center space-x-1"
                                >
                                    <Copy className="w-4 h-4" />
                                    <span>复制</span>
                                </button>
                            </div>
                        </div>
                    )}
                </div>

                {/* Orders Section */}
                <div className="bg-white dark:bg-gray-800 p-4">
                    <div className="flex items-center space-x-4 mb-4">
                        <button
                            onClick={() => setActiveTab('orders')}
                            className={`flex items-center space-x-2 px-4 py-2 rounded-lg font-medium transition-colors ${
                                activeTab === 'orders'
                                    ? 'bg-blue-500 text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                            }`}
                        >
                            <ShoppingBag className="w-5 h-5" />
                            <span>我的订单</span>
                        </button>
                        <button
                            onClick={() => setActiveTab('referrals')}
                            className={`flex items-center space-x-2 px-4 py-2 rounded-lg font-medium transition-colors ${
                                activeTab === 'referrals'
                                    ? 'bg-blue-500 text-white'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                            }`}
                        >
                            <UserCheck className="w-5 h-5" />
                            <span>直推订单</span>
                        </button>
                    </div>

                    {/* Orders List */}
                    <div className="space-y-3 pb-20">
                        {(activeTab === 'orders' ? myOrders : referralOrders).map((order) => (
                            <Card key={order.id} className="border-0 shadow-sm hover:shadow-md transition-shadow">
                                <CardContent className="p-4">
                                    <div className="flex items-center justify-between mb-3">
                                        <div className="flex items-center space-x-3">
                                            <div className="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <ShoppingBag className="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                            </div>
                                            <div>
                                                <h4 className="font-medium text-gray-900 dark:text-white">
                                                    {order.title}
                                                </h4>
                                                <p className="text-sm text-gray-500 dark:text-gray-400">
                                                    订单号: {order.id}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <div className="text-lg font-semibold text-gray-900 dark:text-white">
                                                ¥{order.amount}
                                            </div>
                                            <Badge className={`text-xs ${getStatusColor(order.status)}`}>
                                                {getStatusText(order.status)}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <span>{order.date}</span>
                                        {order.referrer && (
                                            <span className="text-blue-500">推荐人: {order.referrer}</span>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                        
                        {(activeTab === 'orders' ? myOrders : referralOrders).length === 0 && (
                            <div className="text-center py-8 text-gray-500 dark:text-gray-400">
                                <ShoppingBag className="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>暂无订单记录</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </MobileLayout>
    );
}