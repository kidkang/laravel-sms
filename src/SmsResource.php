<?php

namespace Yjtec\LaravelSms;

use Illuminate\Http\Resources\Json\Resource;

class SmsResource extends Resource
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

    public function with($request){
        return[
            'errcode' => 0,
            'errmsg' => '发送成功'
        ];
    }
}
