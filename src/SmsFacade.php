<?php
namespace Yjtec\LaravelSms;
use Illuminate\Support\Facades\Facade;
class SmsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}