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
            set_error_handler('_seqErrorHandler');
        }
        else
            error_reporting(0);
    }

    private function _seqErrorHandler($code = '', $msg = '', $file = '', $line = '')
    {
        switch ($code)
        {
            case E_ERROR:
                self::_secLog('Script Error', "line: $line script: $file error: $code reason: $msg");
                break;
            case E_WARNING:
                self::_secLog('Script Warning', "line: $line script: $file error: $code reason: $msg");
                break;
            case E_NOTICE:
                self::_secLog('Script Notice', "line: $line script: $file error: $code reason: $msg");
                break;
            default:
                break;
        }
    }

    /**
     * Logfile output
     */
    private static function _secLog($message = '', $testName = '', $source = '')
    {
        if (self::$_securityCore->_secLog)
        {
            $rootdir = self::$_securityCore->_secBaseDir;
            $logfile = fopen($rootdir . "seq_log/log.txt", "a");
            fputs($logfile, date("d.m.Y, H:i:s", time()) .
                    ", " . $_SERVER['REMOTE_ADDR'] .
                    ", [" . $source . "]" .
                    ", " . $message .
                    ", " . $testName .
                    ", " . $_SERVER['REQUEST_METHOD'] .
                    ", " . $_SERVER['PHP_SELF'] .
                    ", " . $_SERVER['HTTP_USER_AGENT'] .
                    ", " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') .
                    "\n");
            fclose($logfile);
        }
    }

}