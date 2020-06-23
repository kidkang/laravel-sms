<?php

namespace Yjtec\LaravelSms;

use Illuminate\Contracts\Validation\Rule;
use Request;

class SmsRule implements Rule
{
    private $type = "default";
    private $phone;

    private $msg = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($phone, $type = 'default')
    {
        $this->phone = $phone;
        $this->type = $type;
    }

    public function getMessage()
    {
        return $this->msg;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $phone = Request::get($this->phone);
        $result = app('sms')->type($this->type)->check($phone, $value);
        if ($result['code'] === 0) {
            return true;
        } else {
            $this->msg = $result['message'];
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }

    public static function getPhonePregMatchRule()
    {
        return '/^((13[0-9])|(14[5-9])|(15[0-3,5-9])|(16[2,5,6,7])|(17[0-3,4-8])|(18[0-9])|191|193|198|199)\d{8}$/';
    }
}
