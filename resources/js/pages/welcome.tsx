import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    const handleButtonClick = () => {
        // 触感反馈
        if (navigator.vibrate) {
            navigator.vibrate(20); // 按钮点击震动20ms
        }
    };

    return (
        <>
            <Head title="同城交友搭子群 - 寻找你的完美活动伙伴">
                <link rel="preconnect" href="https://fonts.gstatic.com/" crossOrigin="" />
                <link
                    rel="stylesheet"
                    as="style"
                    onLoad="this.rel='stylesheet'"
                    href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Plus+Jakarta+Sans:wght@400;500;700;800"
                />
            </Head>
            
            <div
                className="relative flex size-full min-h-screen flex-col bg-gradient-to-br from-slate-50 to-blue-50 justify-between group/design-root overflow-x-hidden"
                style={{ fontFamily: '"Plus Jakarta Sans", "Noto Sans", sans-serif' }}
            >
                <div className="flex-1 flex flex-col items-center justify-center px-6 py-12">
                    {/* Hero Section */}
                    <div className="max-w-4xl mx-auto text-center space-y-8">
                        {/* Banner Image - 95% screen width, auto height */}
                        <div className="flex justify-center w-full">
                            <img 
                                src="/images/banner.png"
                                alt="同城交友搭子群"
                                className="w-[95%] h-auto rounded-2xl shadow-2xl border border-white/20"
                            />
                        </div>
                        
                        {/* Main Title */}
                        <div className="space-y-4">
                            <h1 className="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                                <span className="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    同城搭子网
                                </span>
                            </h1>
                            <h2 className="text-2xl md:text-3xl font-bold text-gray-700 leading-tight">
                                今日就找到你的完美活动伙伴！
                            </h2>
                        </div>
                        
                        {/* Description */}
                        <p className="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                            与志同道合的人建立联系，分享你的兴趣爱好。发现新的活动，建立持久的友谊。
                        </p>
                        
                        {/* Features */}
                        
                    </div>
                </div>
                
                {/* Action Buttons */}
                <div className="pb-12">
                    <div className="flex flex-col sm:flex-row gap-4 justify-center items-center px-6">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 min-w-[200px] text-center active:scale-95"
                                onClick={handleButtonClick}
                                onTouchStart={(e) => {
                                    e.currentTarget.classList.add('scale-95', 'shadow-lg');
                                    handleButtonClick();
                                }}
                                onTouchEnd={(e) => {
                                    setTimeout(() => {
                                        e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                                    }, 100);
                                }}
                            >
                                <span className="relative z-10">进入社区</span>
                                <div className="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('dashboard')}
                                    className="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 min-w-[200px] text-center active:scale-95"
                                    onClick={handleButtonClick}
                                    onTouchStart={(e) => {
                                        e.currentTarget.classList.add('scale-95', 'shadow-lg');
                                        handleButtonClick();
                                    }}
                                    onTouchEnd={(e) => {
                                        setTimeout(() => {
                                            e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                                        }, 100);
                                    }}
                                >
                                    <span className="relative z-10">加入社区</span>
                                    <div className="absolute inset-0 bg-gradient-to-r from-blue-700 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </Link>
                                
                                <Link
                                    href={route('dashboard')}
                                    className="bg-white/80 backdrop-blur-sm text-gray-700 px-8 py-4 rounded-2xl font-semibold text-lg border border-gray-200 hover:bg-white hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 min-w-[200px] text-center active:scale-95"
                                    onClick={handleButtonClick}
                                    onTouchStart={(e) => {
                                        e.currentTarget.classList.add('scale-95', 'shadow-lg');
                                        handleButtonClick();
                                    }}
                                    onTouchEnd={(e) => {
                                        setTimeout(() => {
                                            e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                                        }, 100);
                                    }}
                                >
                                    已有账号？登录
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}