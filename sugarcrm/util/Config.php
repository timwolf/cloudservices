<?php

class Config
{
    public static function getEmailServiceProvider()
    {
        return array(
            "provider_name" => 'Mandrill',
            "account_id"    => '7uzEGd38lB9r6XwSQ0ZJpQ',
            "account_password" => '',
        );
    }
}
