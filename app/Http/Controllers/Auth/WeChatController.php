<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class WeChatController extends Controller
{
    /**
     * Redirect to WeChat OAuth
     */
    public function redirect()
    {
        return Socialite::driver('weixin')->scopes(['snsapi_base'])->redirect();
    }

    /**
     * Handle WeChat OAuth callback
     */
    public function callback(Request $request)
    {
        try {
            $wechatUser = Socialite::driver('weixin')->user();
            
            // Check if user exists by WeChat openid
            $user = User::where('wechat_openid', $wechatUser->getId())->first();
            
            if ($user) {
                // User exists, update info and login
                $user->update([
                    'name' => $wechatUser->getName() ?: $user->name,
                    'avatar' => $wechatUser->getAvatar() ?: $user->avatar,
                    'wechat_nickname' => $wechatUser->getNickname(),
                    'wechat_avatar' => $wechatUser->getAvatar(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $wechatUser->getName() ?: $wechatUser->getNickname() ?: '微信用户',
                    'email' => $wechatUser->getEmail() ?: $wechatUser->getId() . '@wechat.local',
                    'password' => Hash::make(Str::random(32)), // Random password
                    'wechat_openid' => $wechatUser->getId(),
                    'wechat_nickname' => $wechatUser->getNickname(),
                    'wechat_avatar' => $wechatUser->getAvatar(),
                    'avatar' => $wechatUser->getAvatar(),
                    'email_verified_at' => now(), // Auto-verify WeChat users
                ]);
            }

            // Login the user
            Auth::login($user, true);

            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            // Handle error
            return redirect()->route('login')->with('error', '微信登录失败，请重试');
        }
    }
}