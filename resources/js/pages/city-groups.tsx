import { useState } from 'react';
import { Head } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import MobileTabNavigation from '@/components/mobile-tab-navigation';
import { ArrowLeft, Users, Lock, CreditCard, X } from 'lucide-react';

interface GroupItem {
  id: number;
  group_name: string;
  nickname: string;
  tags: string;
  current_members: number;
  capacity: number;
  price: number;
  qr_code_logo: string;
  group_logo: string;
  description: string;
  is_hot: boolean;
}

interface CityGroupsProps {
  province: string;
  city: string;
  groups: GroupItem[];
  level?: string;
  district?: string;
}

export default function CityGroups({ province, city, groups = [], level, district }: CityGroupsProps) {
  // 直辖市列表
  const directMunicipalities = ['北京', '上海', '天津', '重庆'];
  
  // 获取正确的显示名称
  const getDisplayName = (province: string, city: string) => {
    if (directMunicipalities.includes(province)) {
      return city; // 直辖市只显示城市名
    }
    return `${province}${city}`; // 其他省份显示省+市
  };
  
  const [selectedGroup, setSelectedGroup] = useState<GroupItem | null>(null);
  const [showQRCode, setShowQRCode] = useState(false);
  const [isPaid, setIsPaid] = useState(false);

  const handleGroupClick = (group: GroupItem) => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(15); // 轻微震动15ms
    }
    
    setSelectedGroup(group);
    setShowQRCode(true);
    setIsPaid(false); // 重置付费状态
  };

  const handleBackClick = () => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(10); // 返回按钮轻微震动
    }
    
    window.history.back();
  };

  const closeQRCode = () => {
    setShowQRCode(false);
    setSelectedGroup(null);
    setIsPaid(false);
  };

  const handlePayment = () => {
    // 触感反馈
    if (navigator.vibrate) {
      navigator.vibrate(25); // 支付按钮用稍长的震动
    }
    
    // 这里应该调用实际的支付接口
    setIsPaid(true);
  };

  const getTagsArray = (tags: string) => {
    return tags ? tags.split(',').slice(0, 3) : []; // 最多显示3个标签
  };

  const getTagColor = (tag: string) => {
    const colors = {
      '交友': 'bg-blue-100 text-blue-800',
      '脱单': 'bg-pink-100 text-pink-800',
      '征婚': 'bg-red-100 text-red-800',
      '美食搭子': 'bg-orange-100 text-orange-800',
      '运动健身': 'bg-green-100 text-green-800',
      '旅游': 'bg-purple-100 text-purple-800',
      '文艺交流': 'bg-indigo-100 text-indigo-800',
      '职场交流': 'bg-gray-100 text-gray-800',
      '学习交流': 'bg-yellow-100 text-yellow-800',
      '游戏娱乐': 'bg-teal-100 text-teal-800',
      '兴趣爱好': 'bg-cyan-100 text-cyan-800',
      '商务合作': 'bg-slate-100 text-slate-800',
      '生活分享': 'bg-lime-100 text-lime-800',
      '情感咨询': 'bg-rose-100 text-rose-800',
    };
    return colors[tag as keyof typeof colors] || 'bg-gray-100 text-gray-800';
  };

  const getTitle = () => {
    if (level === 'city') {
      return `${getDisplayName(province, city)}市级交友群`;
    } else if (district) {
      return `${getDisplayName(province, city)}${district}交友群`;
    } else {
      return `${getDisplayName(province, city)}同城交友群`;
    }
  };

  const getSubtitle = () => {
    if (level === 'city') {
      return '市级群组';
    } else if (district) {
      return `${district}县区群组`;
    } else {
      return '同城群组';
    }
  };

  return (
    <>
      <Head title={getTitle()} />
      <div className="max-w-sm mx-auto bg-white min-h-screen">
        {/* Header */}
        <div className="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4">
          <div className="flex items-center space-x-3">
            <button
              onClick={handleBackClick}
              className="p-2 hover:bg-white/20 rounded-full transition-colors"
            >
              <ArrowLeft className="w-5 h-5" />
            </button>
            <div>
              <h1 className="text-lg font-bold">{getTitle()}</h1>
              <p className="text-blue-100 text-sm">{getSubtitle()} · 共 {groups.length} 个群组</p>
            </div>
          </div>
        </div>

        {/* Groups Grid */}
        <div className="p-4 pb-20">
          <div className="grid grid-cols-2 gap-4">
            {groups.map((group) => (
              <div
                key={group.id}
                className="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer relative transform active:scale-95 hover:scale-105 active:bg-blue-50"
                onClick={() => handleGroupClick(group)}
                onTouchStart={(e) => {
                  e.currentTarget.classList.add('bg-blue-50', 'scale-95');
                }}
                onTouchEnd={(e) => {
                  setTimeout(() => {
                    e.currentTarget.classList.remove('bg-blue-50', 'scale-95');
                  }, 100);
                }}
              >
                <div className="p-2">
                  {/* Top Row: Logo and Info */}
                  <div className="flex items-center space-x-2 mb-1">
                    {/* Group Logo */}
                    <div className="relative flex-shrink-0">
                      <img
                        src={group.group_logo || `/images/groups/qun${group.id % 10}.jpg`}
                        alt={group.nickname}
                        className="w-16 h-16 rounded-lg object-cover"
                        onError={(e) => {
                          // 如果图片加载失败，显示默认渐变背景
                          const target = e.target as HTMLImageElement;
                          target.style.display = 'none';
                          const parent = target.parentElement;
                          if (parent) {
                            parent.innerHTML = `
                              <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center">
                                <div class="text-white font-bold text-sm">${group.nickname.replace('群', '')}</div>
                              </div>
                            `;
                          }
                        }}
                      />
                    </div>

                    {/* Group Info */}
                    <div className="flex-1">
                      {/* Group Nickname with Full Status */}
                      <div className="mb-0.5">
                        <h3 className="font-semibold text-gray-900 text-sm">
                          {group.nickname}
                          {group.current_members >= group.capacity && (
                            <span className="text-red-500 text-xs ml-1">（已满）</span>
                          )}
                        </h3>
                      </div>
                      
                      {/* Member Count */}
                      <div className="flex items-center text-xs text-gray-600 mt-0.5">
                        
                        <span>{group.current_members}/{group.capacity}</span>
                      </div>
                    </div>
                  </div>

                  {/* Bottom Row: Single Tag */}
                  <div className="flex justify-start mt-1">
                    {getTagsArray(group.tags)[0] && (
                      <Badge
                        className={`text-xs px-1.5 py-0.5 ${getTagColor(getTagsArray(group.tags)[0])}`}
                      >
                        {getTagsArray(group.tags)[0]}
                      </Badge>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Empty State */}
          {groups.length === 0 && (
            <div className="text-center py-12">
              <div className="text-gray-400 mb-4">
                <Users className="w-16 h-16 mx-auto mb-4 opacity-50" />
                <p className="text-lg font-medium mb-2">暂无群组</p>
                <p className="text-sm">该城市暂时没有可用的群组</p>
              </div>
            </div>
          )}
        </div>

        {/* QR Code Modal */}
        {showQRCode && selectedGroup && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div className="bg-white rounded-xl max-w-sm w-full max-h-[90vh] overflow-y-auto">
              <div className="p-6">
                <div className="flex justify-between items-center mb-4">
                  <h3 className="text-lg font-semibold text-gray-900">扫码入群</h3>
                  <button
                    onClick={closeQRCode}
                    className="text-gray-400 hover:text-gray-600"
                  >
                    <X className="w-6 h-6" />
                  </button>
                </div>
                
                <div className="text-center mb-4">
                  <div className="inline-flex items-center justify-center w-16 h-16 rounded-lg mb-3 overflow-hidden">
                    <img
                      src={selectedGroup.group_logo || `/images/groups/qun${selectedGroup.id % 10}.jpg`}
                      alt={selectedGroup.nickname}
                      className="w-full h-full object-cover rounded-lg"
                      onError={(e) => {
                        // 如果图片加载失败，显示默认渐变背景
                        const target = e.target as HTMLImageElement;
                        target.style.display = 'none';
                        const parent = target.parentElement;
                        if (parent) {
                          parent.innerHTML = `
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center">
                              <div class="text-white font-bold text-lg">${selectedGroup.nickname.replace('群', '')}</div>
                            </div>
                          `;
                        }
                      }}
                    />
                  </div>
                  <h4 className="font-medium text-gray-900">{city}市同城交友搭子{selectedGroup.nickname}</h4>
                </div>

                {/* QR Code Area */}
                <div className="relative mb-4">
                  <div className="w-36 h-36 mx-auto border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                    {isPaid ? (
                      <img
                        src={selectedGroup.qr_code_logo || '/images/demo-qr.png'}
                        alt="群二维码"
                        className="w-full h-full object-contain rounded-lg"
                      />
                    ) : (
                      <div className="relative w-full h-full">
                        <img
                          src={selectedGroup.qr_code_logo || '/images/demo-qr.png'}
                          alt="群二维码"
                          className="w-full h-full object-contain rounded-lg"
                        />
                        {/* Center Mosaic Overlay - Only covers center portion */}
                        <div className="absolute inset-0 rounded-lg flex items-center justify-center">
                          <div className="w-24 h-24 rounded-lg flex items-center justify-center" style={{
                            background: `
                              radial-gradient(circle at 25% 25%, rgba(0,0,0,0.9) 2px, transparent 2px),
                              radial-gradient(circle at 75% 25%, rgba(0,0,0,0.9) 2px, transparent 2px),
                              radial-gradient(circle at 25% 75%, rgba(0,0,0,0.9) 2px, transparent 2px),
                              radial-gradient(circle at 75% 75%, rgba(0,0,0,0.9) 2px, transparent 2px),
                              radial-gradient(circle at 50% 50%, rgba(0,0,0,0.9) 2px, transparent 2px),
                              linear-gradient(45deg, rgba(0,0,0,0.7), rgba(0,0,0,0.8))
                            `,
                            backgroundSize: '12px 12px, 14px 14px, 16px 16px, 18px 18px, 20px 20px, 100% 100%'
                          }}>
                            <div className="text-center bg-black bg-opacity-70 rounded-lg px-2 py-1">
                              <Lock className="w-6 h-6 text-white mx-auto mb-1" />
                              <p className="text-white text-xs font-bold">
                                {selectedGroup.price > 0 ? '付费查看' : '需要验证'}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    )}
                  </div>
                </div>

                <div className="space-y-2 mb-4">
                  <div className="flex justify-between items-center text-sm">
                    <span className="text-gray-600">当前人数:</span>
                    <span className="font-medium">{selectedGroup.current_members}/{selectedGroup.capacity}</span>
                  </div>
                  <div className="flex justify-between items-center text-sm">
                    <span className="text-gray-600">入群费用:</span>
                    <span className="font-medium text-red-600">
                      {selectedGroup.price > 0 ? `¥${selectedGroup.price}` : '免费'}
                    </span>
                  </div>
                </div>

                {/* Tags */}
                <div className="mb-4">
                  <p className="text-sm text-gray-600 mb-2">群标签:</p>
                  <div className="flex flex-wrap gap-1">
                    {getTagsArray(selectedGroup.tags).map((tag, index) => (
                      <Badge
                        key={index}
                        className={`text-xs px-2 py-1 ${getTagColor(tag)}`}
                      >
                        {tag}
                      </Badge>
                    ))}
                  </div>
                </div>

                {/* 群介绍说明 - 付费前显示 */}
                {!isPaid && (
                  <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h4 className="text-blue-800 font-medium text-sm mb-2">群介绍</h4>
                    <p className="text-blue-700 text-xs leading-relaxed">
                      {selectedGroup.description || '本群是高质量同城搭子群，每天免费邀请同城高质量美女进群，欢迎多来点高质量男性帅哥，付费只是为筛选高质量人群。单身交友、露营、烧烤、爬山、钓鱼、聚会、电影、自驾游、健身、游戏、运动等……另加入以后拥有分销权限，分享你的邀请码可获得80%的佣金。'}
                    </p>
                  </div>
                )}

                {/* Action Buttons */}
                <div className="space-y-3">
                  {!isPaid && selectedGroup.price > 0 ? (
                    <button
                      onClick={handlePayment}
                      className="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition-all transform active:scale-95 hover:scale-105 active:shadow-lg flex items-center justify-center"
                      onTouchStart={(e) => {
                        e.currentTarget.classList.add('scale-95', 'shadow-lg');
                      }}
                      onTouchEnd={(e) => {
                        setTimeout(() => {
                          e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                        }, 100);
                      }}
                    >
                      <CreditCard className="w-5 h-5 mr-2" />
                      支付 ¥{selectedGroup.price} 查看二维码
                    </button>
                  ) : !isPaid ? (
                    <button
                      onClick={handlePayment}
                      className="w-full bg-green-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-600 transition-all transform active:scale-95 hover:scale-105 active:shadow-lg"
                      onTouchStart={(e) => {
                        e.currentTarget.classList.add('scale-95', 'shadow-lg');
                      }}
                      onTouchEnd={(e) => {
                        setTimeout(() => {
                          e.currentTarget.classList.remove('scale-95', 'shadow-lg');
                        }, 100);
                      }}
                    >
                      免费获取二维码
                    </button>
                  ) : (
                    <div className="text-center">
                      <div className="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                        <p className="text-green-700 text-sm font-medium">
                          ✓ 已成功获取二维码
                        </p>
                        <p className="text-green-600 text-xs mt-1">
                          使用微信扫描上方二维码即可加入群聊
                        </p>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
      <MobileTabNavigation />
    </>
  );
}