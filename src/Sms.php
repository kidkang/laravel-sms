<?php

namespace Yjtec\LaravelSms;

use Cache;

class Sms
{
    protected $config;
    protected $type = 'default';
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function send($phone)
    {
        $expiredAt = now()->addMinutes(5);
        if (config('sms.debug', false)) {
            $code = config('sms.default', 123456);
        } else {
            $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
            $result = (new SmsSend(
                config('sms.key'),
                config('sms.secret'),
                config('sms.sign_name')
            ))->send($phone, $code, config('sms.tpl'));
        }
        $key = 'verificationCode_' . $this->type . '_' . $phone;
        $expiredAt = now()->addMinutes(5);
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        return [
            'code' => $code,
            'expiredAt' => $expiredAt,
        ];
    }

    /**
     * 校验验证码是否正确
     * @param $phone
     * @param $code
     * @return array
     */
    public function check($phone, $code, $isRefresh = true)
    {
        $key = 'verificationCode_' . $this->type . '_' . $phone;
        $verifyData = Cache::get($key);

        if (!$verifyData) {
            return [
                'code' => 1002,
                'message' => '验证码错误',
            ];
        }

        if (!hash_equals($verifyData['code'], "$code") || !hash_equals($verifyData['phone'], "$phone")) {
            return [
                'code' => 1003,
                'message' => '验证码错误',
            ];
        }
        if ($isRefresh == true) {
            Cache::forget($key);
        }
        return [
            'code' => 0,
            'message' => '验证成功',
        ];
    }

}
