<?php
namespace Yjtec\LaravelSms;

use DB;
use Illuminate\Validation\Validator;

class SmsValidator extends Validator
{
    public function __construct($translator, $data, $rules, $messages)
    {
        parent::__construct($translator, $data, $rules, $messages);
    }

    public function validateSms($attribute, $value, $parameters, $validator)
    {
        $type = false;
        if(count($parameters) == 1){
            list($phoneField) = $parameters;
        }else{
            $typeFlag = true;
            list($phoneField,$type) = $parameters;
        }
        $sms = app('sms');

        if($type){
            $sms->type($type);
        }
        $phone = $this->getValue($phoneField);
        $code = $sms->check($phone,$value);
        if($code['code'] === 1){
            return true;
        }else{
            $this->setCustomMessages(['sms'=>$code['message']]);
            return false;
        }
        
    }
}
