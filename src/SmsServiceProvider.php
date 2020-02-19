<?php

namespace Yjtec\LaravelSms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Validator::resolver(function($translator, $data, $rules, $messages){
        //     return new SmsValidator($translator, $data, $rules, $messages);
        // });
        Validator::extend('sms', function ($attribute, $value, $parameters, $validator) {
            $smsValidator = $this->app->makeWith(
                'Yjtec\LaravelSms\SmsRule',
                [
                    'phone' => $parameters[0],
                    'type'  => isset($parameters[1]) && $parameters[1] ? $parameters[1] : 'default',
                ]
            );
            $flag = $smsValidator->passes($attribute, $value);
            if (!$flag) {
                $validator->errors()->add('error', $smsValidator->getMessage());
            }
            return $flag;
        });
        $this->publishes([
            __DIR__ . '/database/'  => database_path('migrations'),
            __DIR__ . '/config.php' => config_path('sms.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sms', function ($app) {
            return new Sms(config('sms'));
        });
    }
}
