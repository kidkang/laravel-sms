<?php 
/*
sms config
 */
return [
    'type'      => ['login', 'register', 'order'], //['login','register','order']
    'default'   => '111111',
    'rules' => [
        'login' => ['required','unique']
    ],
    'debug'     => false,
    'key'       => env('SMS_KEYID', ''),
    'secret'    => env('SMS_KEYSECRET', ''),
    'sign_name' => env('SMS_SING_NAME', ''),
    'tpl'       => env("SMS_TPL", ""),
];

/**
 * @OA\Schema(
 *      schema="SmsTypesDefault",
 *      enum={"login","register","order"}
 *  )
 */
?>