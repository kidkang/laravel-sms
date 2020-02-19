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
}
