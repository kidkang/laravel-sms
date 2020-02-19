<?php

namespace Yjtec\LaravelSms;

use Illuminate\Database\Eloquent\Model;

class SmsModel extends Model
{
    protected $table    = "sms";
    protected $fillable = ['phone', 'code', 'type', 'expired_at'];
}
