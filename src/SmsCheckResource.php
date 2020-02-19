<?php

namespace Yjtec\LaravelSms;

use Illuminate\Http\Resources\Json\Resource;

class SmsCheckResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
        ];
    }

    public function with($request)
    {
        $result = parent::toArray($this);
        $return = array(
            'errcode' => isset($result['code']) ? $result['code'] : 1,
            'errmsg' => isset($result['message']) ? $result['message'] : '未知错误',
        );
        return $return;
    }
}
