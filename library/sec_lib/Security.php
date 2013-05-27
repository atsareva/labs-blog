<?php

require_once 'SecConfig.php';

class Security
{

    private static $_secConfig       = '';
    private static $_httpCookieVars  = array();
    private static $_httpSessionVars = array();
    public static $_secDebug        = 0;
    public static $_secTokenName    = 'secTokenName';
    public static $_secAppSalt      = '';

    public static function run()
    {
        self::$_secConfig = new SecConfig();

        restore_error_handler();
        if (self::$_secDebug || self::$_secConfig->_secErrors)
        {
            error_reporting(E_USER_ERROR | E_USER_WARNING);
            set_error_handler('_seqErrorHandler');
        }
        else
            error_reporting(0);

        self::_secAppSalt();
    }

    /**
     * Generates unique SALT value to be used with all MD5 hashes.
     * Salt is valid until salt file is removed (normally never)
     */
    private static function _secAppSalt()
    {
        if (!file_exists(self::$_secConfig->_secBaseDir . 'app_salt.txt'))
        {
            $applicationSalt = md5(uniqid(rand(), TRUE));
            file_put_contents(self::$_secConfig->_secBaseDir . 'app_salt.txt', $applicationSalt);
            chmod($logFile, 0777);

            self::$_secAppSalt = $applicationSalt;
        }
        else
            self::$_secAppSalt = file_get_contents(self::$_secConfig->_secBaseDir . 'app_salt.txt');
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
        if (self::$_secConfig->_secLog)
        {
            $rootdir = self::$_secConfig->_secBaseDir;
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

    /**
     * Sets additional security to session data and session cookie.
     * Has to be called after the application fully initiates its session.
     *
     * @return boolean
     */
    public function secSecureSession()
    {
        if (!self::$_secConfig->_secSecureSession)
            return FALSE;

        if (!isset($_SESSION) && !isset(self::$_httpSessionVars))
        {
            self::_secLog('secSecureSession: no SESSION found at execution time. Call secSecureSession after session start.', '');
            return false;
        }

        $sessionData = '';
        (ini_get('register_long_arrays') && isset(self::$_httpSessionVars)) ? $sessionData = self::$_httpSessionVars : $sessionData = $_SESSION;

        if (!isset($sessionData['SEC']))
            $sessionData['SEC'] = array();

        if (!isset($sessionData['SEC']['session_touchtime']))
        {
            if (self::$_secConfig->_secSecureCookies)
            {
                if (function_exists('ini_set'))
                {
                    ini_set('session.cookie_lifetime', self::$_secConfig->_secSessionLifeTime);
                    ini_set('session.cookie_httponly', true);
                }
                if (function_exists('session_set_cookie_params'))
                {
                    $cookieData = session_get_cookie_params();
                    session_set_cookie_params(self::$_secConfig->_secSessionLifeTime, $cookieData['path'], $cookieData['domain'], $cookieData['secure'], true);
                }
            }
            session_regenerate_id(true);

            $sessionData = array(
                'SEC' => array(
                    'session_touchtime'    => time(),
                    'session_creationtime' => time(),
                )
            );

            if (self::$_secConfig->_secSessionHeadersCheck)
                $sessionData['SEC']['agent_key'] = self::_secUseragentFingerprint();
        }
        else if (self::$_secConfig->_secSessionRefresh == 0 || isset($sessionData['SEC']['session_touchtime']))
        {

            if (isset($sessionData['SEC']['session_creationtime']) && (time() - $sessionData['SEC']['session_creationtime']) > self::$_secConfig->_secSessionAbsoluteLifeTime)
            {
                self::_secLog('SESSION TERMINATED: absolute sessionlifetime expired', '');
                self::_secTerminateSession();
            }

            if (isset($sessionData['SEC']['agent_key']))
            {
                if ($sessionData['SEC']['agent_key'] != self::_secUseragentFingerprint())
                {
                    self::_secLog('SESSION TERMINATED: Agent Fingerprint Changed.', '');
                    self::_secTerminateSession();
                }
            }

            $sessionAge = time() - $sessionData['SEC']['session_touchtime'];
            if (self::$_secConfig->_secSessionRefresh == 0 || $sessionAge > self::$_secConfig->_secSessionRefresh)
            {
                if (!headers_sent())
                    session_regenerate_id(true);
            }
        }

        $sessionData['SEC']['session_touchtime'] = time();

        if (ini_get('register_long_arrays') && isset(self::$_httpSessionVars))
            self::$_httpSessionVars = $sessionData;

        $_SESSION = $sessionData;
    }

    /**
     * Terminates current session and unsets all session content.
     */
    private static function _secTerminateSession($redirectExit = true)
    {
        $seqSessName = self::$_secConfig->_secSessionName ? self::$_secConfig->_secSessionName : session_name();

        // expire cookie
        if (self::$_secConfig->_secSecureCookie && ($_COOKIE || self::$_httpCookieVars) && isset($_COOKIE[$seqSessName]) && !headers_sent())
        {
            // could we be too early to know 'path' or 'domain' settings?
            $cookieData = session_get_cookie_params();
            setcookie($seqSessName, '', time() - self::$_secConfig->_secSessionLifeTime, $cookieData['path'], $cookieData['domain']);

            if (isset($_SESSION))
                $_COOKIE = array();

            if (isset(self::$_httpCookieVars))
                self::$_httpCookieVars = array();
        }

        // unset session variables
        if (isset($_SESSION))
            $_SESSION = array();

        if (isset(self::$_httpSessionVars))
            self::$_httpSessionVars = array();

        session_unset();

        if ($redirectExit)
        {
            // redirect to location OR
            self::_secTerminate('redirect');
            die;
        }
    }

    /**
     * Generates Useragent fingerprint
     *
     * @return string
     */
    private static function _secUseragentFingerprint()
    {
        /* With IE 6.0 HTTP_ACCEPT changes between requests. Not usefull! */
        $fingerprint = $_SERVER['HTTP_USER_AGENT'] . self::_secAppSalt();
        self::_secDebug($fingerprint);
        return md5($fingerprint);
    }

    /**
     * Terminates script execution
     */
    private static function _secTerminate($reason = '')
    {
        // better to redirect in any case? it is less informative!
        switch ($reason)
        {
            case 'err':
                echo "<b>Undefined action.</b>";
                die;
                break;
            case 'redirect':
                if (!headers_sent() && !empty(self::$_secConfig->_secOnerrorRedirectTo))
                    header("Location: " . self::$_secConfig->_secOnerrorRedirectTo);
                else
                    echo "<b>Undefined action.</b>";
                die;
                break;
            default:
                echo "<b>Illegal action.</b>";
                die;
        }
    }

    public static function _secDebug($string = '')
    {
        if (self::$_secConfig->_secDebug)
            echo "<br>------" . $string . "<br>";
    }

}