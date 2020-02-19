<?php
namespace Yjtec\LaravelSms;

use Overtrue\EasySms\EasySms;

class SmsSend
{
    private $config = [
        'timeout'  => 5.0,
        'default'  => [
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
            'gateways' => [
                'aliyun',
            ],
        ],
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
        ],
    ];
    public function __construct($appid, $secret, $sign_name)
    {
        $this->config['gateways']['aliyun'] = [
            'access_key_id'     => $appid,
            'access_key_secret' => $secret,
            'sign_name'         => $sign_name,
        ];
        $this->obj = new EasySms($this->config);

        return $this;
    }

    public function send($phone, $code, $tpl)
    {
        try {
            return $this->obj->send($phone, [
                'template' => $tpl,
                'data'     => [
                    'code' => $code,
                ],
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            //没有配置错误信息记录
            $message = $exception->getException('aliyun')->getMessage();
            return [
                'code'    => 1001,
                'message' => $message,
            ];
        }

    }

}
