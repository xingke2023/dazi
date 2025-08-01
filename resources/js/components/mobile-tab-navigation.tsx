import { cn } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/react';
import { Home, MessageSquare, Clock, User, Settings } from 'lucide-react';
import { useState } from 'react';

interface TabItem {
    name: string;
    href: string;
    icon: React.ElementType;
    current: boolean;
}

export default function MobileTabNavigation() {
    const { url } = usePage();
    const [activeTab, setActiveTab] = useState<string>('');
    
    const tabs: TabItem[] = [
        {
            name: '首页',
            href: '/dashboard',
            icon: Home,
            current: url === '/dashboard',
        },
        {
            name: '群友风采',
            href: '/latest',
            icon: Clock,
            current: url === '/latest',
        },
        {
            name: '客户服务',
            href: '/hall',
            icon: MessageSquare,
            current: url === '/hall',
        },
        {
            name: '我的',
            href: '/usercenter',
            icon: User,
            current: url === '/usercenter',
        },
    ];

    // 触感反馈函数
    const handleTouchFeedback = (tabName: string) => {
        // 触发触感反馈
        if (navigator.vibrate) {
            navigator.vibrate(10); // 轻微震动10ms
        }
        
        // 设置激活状态
        setActiveTab(tabName);
        
        // 短暂延迟后清除激活状态
        setTimeout(() => {
            setActiveTab('');
        }, 150);
    };

    return (
        <div className="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 dark:bg-gray-900 dark:border-gray-700 shadow-lg">
            <div className="grid grid-cols-4 max-w-md mx-auto">
                {tabs.map((tab) => {
                    const Icon = tab.icon;
                    const isActive = activeTab === tab.name;
                    
                    return (
                        <Link
                            key={tab.name}
                            href={tab.href}
                            className={cn(
                                'flex flex-col items-center justify-center py-3 text-xs font-medium transition-all duration-200 relative overflow-hidden',
                                'active:scale-95 transform',
                                'hover:bg-gray-50 dark:hover:bg-gray-800',
                                'focus:outline-none',
                                tab.current
                                    ? 'text-blue-600 dark:text-blue-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200',
                                isActive && 'bg-blue-50 dark:bg-blue-900/20'
                            )}
                            onTouchStart={() => handleTouchFeedback(tab.name)}
                            onMouseDown={() => handleTouchFeedback(tab.name)}
                        >
                            {/* 点击波纹效果 */}
                            {isActive && (
                                <div className="absolute inset-0 bg-blue-500 opacity-10 rounded-lg animate-ping"></div>
                            )}
                            
                            <Icon
                                className={cn(
                                    'h-6 w-6 mb-1 transition-all duration-200',
                                    tab.current
                                        ? 'text-blue-600 dark:text-blue-400'
                                        : 'text-gray-500 dark:text-gray-400',
                                    isActive && 'scale-110'
                                )}
                            />
                            
                            <span className={cn(
                                'transition-all duration-200',
                                isActive && 'font-semibold'
                            )}>
                                {tab.name}
                            </span>
                            
                            {/* 当前页面指示器 */}
                            {tab.current && (
                                <div className="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-8 h-0.5 bg-blue-600 rounded-full"></div>
                            )}
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}