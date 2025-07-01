<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Config::set('app.timezone', hwa_setting('time_zone'));
        Config::set('app.locale', hwa_setting('locale'));

        Config::set('services.ses', [
            'domain' => hwa_setting('email_mail_gun_domain'),
            'secret' => hwa_setting('email_mail_gun_secret'),
            'endpoint' => hwa_setting('email_mail_gun_endpoint'),
        ]);

        Config::set('services.ses', [
            'key' => hwa_setting('email_ses_key'),
            'secret' => hwa_setting('email_ses_secret'),
            'region' => hwa_setting('email_ses_region'),
        ]);

        Config::set('mail', [
            'driver'     => hwa_setting('email_driver'),
            'host'       => hwa_setting('email_host'),
            'port'       => hwa_setting('email_port'),
            'username'   => hwa_setting('email_username'),
            'password'   => hwa_setting('email_password'),
            'encryption' => hwa_setting('email_encryption'),
            'from'       => array('address' => hwa_setting('email_from_address'), 'name' => hwa_setting('email_from_name')),
            'sendmail'   => '/usr/sbin/sendmail -bs',
            'pretend'    => false,
        ]);
    }
}
