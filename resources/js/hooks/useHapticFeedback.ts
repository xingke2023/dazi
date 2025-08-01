import { useCallback } from 'react';

export type HapticType = 'light' | 'medium' | 'heavy' | 'selection' | 'impact';

/**
 * 触感反馈Hook
 * 提供统一的触感反馈功能
 */
export function useHapticFeedback() {
  const trigger = useCallback((type: HapticType = 'light') => {
    // 检查是否支持触感反馈
    if (typeof navigator !== 'undefined' && navigator.vibrate) {
      let duration: number;
      
      switch (type) {
        case 'light':
          duration = 10;
          break;
        case 'medium':
          duration = 20;
          break;
        case 'heavy':
          duration = 30;
          break;
        case 'selection':
          duration = 5;
          break;
        case 'impact':
          duration = 40;
          break;
        default:
          duration = 10;
      }
      
      navigator.vibrate(duration);
    }
    
    // 如果支持Web Vibration API的更高级功能
    if (typeof navigator !== 'undefined' && 'vibrate' in navigator) {
      switch (type) {
        case 'selection':
          // 短促的选择反馈
          navigator.vibrate([5]);
          break;
        case 'impact':
          // 强烈的冲击反馈
          navigator.vibrate([10, 10, 20]);
          break;
        default:
          // 基本反馈
          navigator.vibrate([10]);
      }
    }
  }, []);

  return { trigger };
}

/**
 * 触感反馈装饰器
 * 为元素添加触感反馈和视觉反馈
 */
export function withHapticFeedback<T extends React.HTMLAttributes<HTMLElement>>(
  Component: React.ComponentType<T>,
  hapticType: HapticType = 'light'
) {
  return function HapticComponent(props: T) {
    const { trigger } = useHapticFeedback();
    
    const handleTouchStart = (e: React.TouchEvent) => {
      trigger(hapticType);
      if (props.onTouchStart) {
        props.onTouchStart(e);
      }
    };
    
    const handleMouseDown = (e: React.MouseEvent) => {
      trigger(hapticType);
      if (props.onMouseDown) {
        props.onMouseDown(e);
      }
    };
    
    return (
      <Component
        {...props}
        onTouchStart={handleTouchStart}
        onMouseDown={handleMouseDown}
      />
    );
  };
}