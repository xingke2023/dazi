<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WeChatPayDirectController extends Controller
{
    private function signRequest($url, $method, $body, $timestamp, $nonceStr)
    {
        $privateKeyPath = storage_path('certs/apiclient_key_wx.pem');
        $privateKey = file_get_contents($privateKeyPath);
        
        $urlPath = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        if ($query) {
            $urlPath .= '?' . $query;
        }
        
        $message = $method . "\n" . 
                  $urlPath . "\n" . 
                  $timestamp . "\n" . 
                  $nonceStr . "\n" . 
                  $body . "\n";
        
        $signature = '';
        openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        return base64_encode($signature);
    }
    
    private function getAuthorizationHeader($url, $method, $body)
    {
        $timestamp = time();
        $nonceStr = Str::random(32);
        $signature = $this->signRequest($url, $method, $body, $timestamp, $nonceStr);
        
        return sprintf(
            'WECHATPAY2-SHA256-RSA2048 mchid="%s",nonce_str="%s",timestamp="%s",serial_no="%s",signature="%s"',
            config('wechat_pay.mch_id'),
            $nonceStr,
            $timestamp,
            config('wechat_pay.cert_serial_no'),
            $signature
        );
    }
    
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:127',
        ]);
        
        try {
            $outTradeNo = 'ORDER_' . date('YmdHis') . '_' . Str::random(6);
            $amount = (int) ($request->amount * 100);
            
            $body = [
                'appid' => config('wechat_pay.app_id'),
                'mchid' => config('wechat_pay.mch_id'),
                'description' => $request->description,
                'out_trade_no' => $outTradeNo,
                'notify_url' => config('wechat_pay.notify_url'),
                'amount' => [
                    'total' => $amount,
                    'currency' => 'CNY',
                ],
                'payer' => [
                    'openid' => $request->user()->wechat_openid ?? 'test_openid',
                ],
            ];
            
            $bodyJson = json_encode($body);
            $url = 'https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi';
            
            $authorization = $this->getAuthorizationHeader($url, 'POST', $bodyJson);
            
            $response = Http::withHeaders([
                'Authorization' => $authorization,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel/EasyWeChat',
            ])->post($url, $body);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['prepay_id'])) {
                    // 生成前端支付配置
                    $paymentConfig = $this->buildPaymentConfig($result['prepay_id']);
                    
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'out_trade_no' => $outTradeNo,
                            'prepay_id' => $result['prepay_id'],
                            'config' => $paymentConfig,
                        ],
                    ]);
                }
            }
            
            Log::error('WeChat Pay API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '创建订单失败: ' . ($response->json()['message'] ?? 'Unknown error'),
            ], 400);
            
        } catch (Exception $e) {
            Log::error('WeChat Pay create order error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => '创建订单失败: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    private function buildPaymentConfig($prepayId)
    {
        $appId = config('wechat_pay.app_id');
        $timeStamp = time();
        $nonceStr = Str::random(32);
        $package = "prepay_id={$prepayId}";
        
        $message = $appId . "\n" . $timeStamp . "\n" . $nonceStr . "\n" . $package . "\n";
        
        $privateKeyPath = storage_path('certs/apiclient_key_wx.pem');
        $privateKey = file_get_contents($privateKeyPath);
        
        $signature = '';
        openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        return [
            'appId' => $appId,
            'timeStamp' => (string) $timeStamp,
            'nonceStr' => $nonceStr,
            'package' => $package,
            'signType' => 'RSA',
            'paySign' => base64_encode($signature),
        ];
    }
}