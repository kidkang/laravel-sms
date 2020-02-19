<?php


namespace Yjtec\LaravelSms;

use Cache;
use Overtrue\EasySms\EasySms;

class VerifcationCodesService
{

    protected $config;
    protected $type;

    public function __construct($type, $config = null)
    {
        $this->config = $config;
        $this->type = $type;
    }

    /**
     * 发送验证码
     * @param $phone [手机号]
     * @return array
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function sendSmsCode($phone)
    {
        if(config('app.env') == 'local'){
            $code = $this->config['default_code']?:'123456';
        }else{
            $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
            $easySms = new EasySms($this->config);
            try {
                $result = $easySms->send($phone, [
                    'template' => $this->config['ali_sms_code_tpl'],
                    'data' => [
                        'code' => $code
                    ]
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                //没有配置错误信息记录
                $message = $exception->getException('aliyun')->getMessage();
                return [
                    'code' => 1001,
                    'message' => $message
                ];
            }
        }
        $key = 'verificationCode_'.$this->type.'_'.$phone;
        $expiredAt = now()->addMinutes(5);
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        return [
            'code' => 1,
            'message' => [
                'sms_code' => $code,
                'expiredAt' => $expiredAt
            ]
        ];
    }

    /**
     * 校验验证码是否正确
     * @param $phone
     * @param $code
     * @return array
     */
    public function checkSmsCode($phone, $code)
    {
        $key = 'verificationCode_'.$this->type.'_'.$phone;
        $verifyData = Cache::get($key);
        if(!$verifyData){
            return [
                'code' => 1002,
                'message' => '验证码过期'
            ];
        }
        if(!hash_equals($verifyData['code'], "$code") || !hash_equals($verifyData['phone'], "$phone")){
            return [
                'code' => 1003,
                'message' => '验证码错误'
            ];
        }
        Cache::forget($key);
        return [
            'code' => 1,
            'message' => '验证成功'
        ];
    }
}