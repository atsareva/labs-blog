<?php

require_once 'core/SecurityCore.php';

class Security
{

    private static $_securityCore = '';
    public static $_secDebug     = 0;
    public static $_secTokenName = 'secTokenName';

    public static function run()
    {
        self::$_securityCore = new SecurityCore();

        restore_error_handler();
        if (self::$_secDebug || self::$_securityCore->_secErrors)
        {
            error_reporting(E_USER_ERROR | E_USER_WARNING);
            set_error_handler('seqErrorHandler');
        }
        else
            error_reporting(0);
    }

    public static function echoSec()
    {
        echo self::$_secTokenName;
    }

}