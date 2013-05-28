<?php

require_once 'SecConfig.php';

class Security
{

    private static $_secConfig    = '';
    public static $_secDebug     = 0;
    public static $_secTokenName = 'secTokenName';
    public static $_secAppSalt   = '';

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
        self::_secSecureSession();
    }

    /**
     * Generate a Token against CSRF-Attacks.
     * Generates a Token to be inserted into a Form.
     * If specific name given, Token will only be valid for that named action.
     *
     * @param string $formName
     * @param bool $once
     * @return string
     */
    public static function secFtoken($formName = '', $once = false)
    {
        return '<input type="hidden" name="' . self::_secCreateTokenName($formName) .
                '" value="' . self::_secCreateTokenValue($formName, $once) . '" />' . "\n";
    }

    /**
     * Generate a Token against CSRF-Attacks.
     * Generates a Token to be inserted into a Link.
     * If specific name given, Token will only be valid for that named action.
     *
     * @param string $linkName
     * @param bool $once
     * @return string
     */
    public static function secLtoken($linkName = '', $once = false)
    {
        return self::_secCreateTokenName($linkName) . '=' . self::_secCreateTokenValue($linkName, $once);
    }

    /**
     * Checks a Token against CSRF-Attacks.
     * Gets Token out of GET/POST-request and checks for validity.
     * If specific name given, Token will only be valid for that named action.
     *
     * @param string $originName
     */
    public static function secCheckToken($originName = '')
    {
        $tokenName = self::_secCreateTokenName($originName);

        $tokenArray = $_SESSION['SEC']['sec_token'];

        if (!isset($tokenArray) || !is_array($tokenArray))
        {
            seq_log_('secCheckToken: no SESSION found at execution time. Call secCheckToken after session start.', '');
            return false;
        }

        $tokenValue = self::_QbHttpVars2Array($tokenName, 'pg');

        if (strlen($tokenValue) == 32)
        {

            if (isset($tokenArray[$tokenName]) && isset($tokenArray[$tokenName]['token']) && $tokenArray[$tokenName]['token'] == $tokenValue)
            {
                $tokenAge = time() - $tokenArray[$tokenName]['time'];
                if ($tokenAge > self::$_secConfig->_secTokenLifeTime)
                {
                    self::_secDebug($tokenAge . ">" . self::$_secConfig->_secTokenLifeTime);
                    self::_secLog('secCheckToken: CSRF token expired', $tokenAge - self::$_secConfig->_secTokenLifeTime);
                    self::_secTerminateSession();
                }

                if ($tokenArray[$tokenName]['once'])
                    unset($_SESSION['SEC']['sec_token'][$tokenName]); // no replay
// SESSION OK
            }
            else
            {
                self::_secLog('secCheckToken: wrong CSRF token', '');
                self::_secTerminateSession();
            }
        }
        else
        {
            self::_secLog('secCheckToken: CSRF token required', $tokenValue);
            self::_secTerminateSession();
        }
    }

    /**
     * Secures input string against XSS-attacks.
     * Return value can be send to browser securely.
     * supports single & multi byte UTF-8
     * 
     * @param string $string
     * @return string
     */
    public static function secOutput($string = '')
    {
        $string = mb_convert_encoding($string, "UTF-8", "7bit, UTF-7, UTF-8, UTF-16, ISO-8859-1, ASCII");
        $string = self::_seqRemoveSlashes($string);
        self::_secCheckIntrusion($string);

        $output = '';

        for ($i = 0; $i < strlen($string); $i++)
        {
            if (preg_match('/([a-zA-Z0-9_.-])/', $string[$i]))
            {
                $output .= $string[$i];
                continue;
            }
            $byte = ord($string[$i]);
            if ($byte <= 127)
            {
                $length = 1;
                $output .= sprintf("&#x%04s;", dechex(self::_uniord(mb_substr($string, $i, $length))));
            }
            else if ($byte >= 194 && $byte <= 223)
            {
                $length = 2;
                $output .= sprintf("&#x%04s;", dechex(self::_uniord(mb_substr($string, $i, $length))));
            }
            else if ($byte >= 224 && $byte <= 239)
            {
                $length = 3;
                $output .= sprintf("&#x%04s;", dechex(self::_uniord(mb_substr($string, $i, $length))));
            }
            else if ($byte >= 240 && $byte <= 244)
            {
                $length = 4;
                $output .= sprintf("&#x%04s;", dechex(self::_uniord(mb_substr($string, $i, $length))));
            }
        }

        return $output;
    }

    private static function _seqRemoveSlashes($string = '')
    {
        $orig     = $string;
        $stripped = stripslashes($orig);

        if ($orig != $stripped)
        {
            $escaped  = addslashes($stripped);
            if ($orig == $escaped)
                $secValue = stripslashes($escaped);
            else
                $secValue = $orig;
        }
        else
            $secValue = $orig;

        return $secValue;
    }

    private static function _uniord($c)
    {
        $h = ord($c{0});
        if ($h <= 0x7F)
            return $h;
        else if ($h < 0xC2)
            return false;
        else if ($h <= 0xDF)
            return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
        else if ($h <= 0xEF)
            return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
        else if ($h <= 0xF4)
            return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
        else
            return false;
    }

    /**
     * Helper for Intrusion Detection System
     */
    private static function _secCheckIntrusion($string = '', $source = '')
    {
        /* array scan is later required */
        if (is_array($string))
            return false;

        $scanValue = $string;
        $matches   = false;

        /* scan for SQL-attack pattern */
        if (preg_match("/(\%27)|(\')|(\')|(%2D%2D)|(\/\*)/i", $scanValue) || /* (\-\-)  deleted. no meaning for MySQL */
                /* (\/\*) added. Comment sign for MySQL */
                preg_match("/\w*(\%27)|'(\s|\+)*((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i", $scanValue) ||
                preg_match("/((\%27)|')(\s|\+)*union/i", $scanValue))
        {
            self::_secLog('SQL Injection detected', $scanValue, $source);
            $matches = true;
        }

        /* scan for XSS-attack pattern */
        if (preg_match("/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i", $scanValue) ||
                preg_match("/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i", $scanValue))
        {
            self::_secLog('XSS detected', $scanValue, $source);
            $matches = true;
        }

        /* scan for Mail-Header-attack pattern */
        if (preg_match("/(Content-Transfer-Encoding:|MIME-Version:|content-type:|Subject:|to:|cc:|bcc:|from:|reply-to:)/ims", $scanValue))
        {
            self::_secLog('Mail-Header Injection detected', $scanValue, $source);
            $matches = true;
        }

        /* scan for "Special chars" pattern */
        if (preg_match("/%0A|\\r|%0D|\\n|%00|\\0|%09|\\t|%01|%02|%03|%04|%05|%06|%07|%08|%09|%0B|%0C|%0E|%0F|%10|%11|%12|%13/i", $scanValue))
        {
            self::_secLog('Special Chars detected', $scanValue, $source);
            $matches = true;
        }

        $matches = self::_secGlobalsOverwrite($scanValue, $source);

        if ($matches)
            self::_secReaction();

        return $matches;
    }

    /**
     * Helper for "globals overwrite" scan
     *
     * @param string $string
     * @param string $source
     * @return boolean
     */
    private static function _secGlobalsOverwrite($string = '', $source = '')
    {
        $matches    = false;
        $globalVars = array(
            '_SERVER',
            '_ENV',
            '_COOKIE',
            '_GET',
            '_POST',
            '_FILES',
            '_REQUEST',
            '_SESSION',
            'GLOBALS'
        );

        if (preg_match("/^(" . implode("|", $globalVars) . ")/", $string, $match))
        {
            self::_secLog('Global VAR overwrite detected', $string, $source);
            $matches = true;
        }
        return $matches;
    }

    /**
     * Executes defined reaction on detected security breach.
     */
    private static function _secReaction($filter = false)
    {
        $action = self::$_secConfig->_secIdsOnAttackAction;

        // call is comming from filter check
        if ($filter)
            $action = self::$_secConfig->_secFilterNoMathAction;

        $actionArray = split(' ', $action);
        if (in_array('delay', $actionArray))
            sleep(50);

        if (in_array('logout', $actionArray))
            self::_secTerminateSession();

        if (in_array('redirect', $actionArray))
        {
            if (!headers_sent() && !empty(self::$_secConfig->_secOnerrorRedirectTo))
            {
                $saveSession = '';

                // if known and found in query string, keep session id when redirect
                if ($_SERVER['QUERY_STRING'])
                {
                    $secSessName = self::$_secConfig->_secSessionName ? self::$_secConfig->_secSessionName : session_name();
                    $queryPairs  = split('&', $_SERVER['QUERY_STRING']);
                    for ($t = 0; $t < length($queryPairs); $t++)
                    {
                        $pairs       = split('=', $queryPairs[$t]);
                        if ($pairs[0] == $secSessName)
                            $saveSession = join($queryPairs[$t]);
                    }
                }

                header("Location: " . self::$_secConfig->_secOnerrorRedirectTo . '?' . $saveSession);
            }
        }
    }

    /**
     * Generates Token name.
     *
     * @param string $originName
     * @return string
     */
    private static function _secCreateTokenName($originName = '')
    {
        $headerHash = '';
        if (self::$_secConfig->_secSessionHeadersCheck)
            $headerHash = self::_secUseragentFingerprint();

        $originName = $originName ? md5($originName . $headerHash . session_id() . self::_secAppSalt()) : md5($headerHash . session_id() . self::_secAppSalt());

        return 'SEQ_TOKEN_' . $originName;
    }

    /**
     * Generates Token value.
     *
     * @param string $originname
     * @param boolean $once
     * @return string
     */
    private static function _secCreateTokenValue($originname = '', $once = false)
    {
        $tokenName = self::_secCreateTokenName($originname);

        if (!isset($_SESSION['SEC']))
        {
            $_SESSION['SEC']              = array();
            $_SESSION['SEC']['sec_token'] = array();
        }

        if (!isset($_SESSION['SEC']['sec_token'][$tokenName]))
            $_SESSION['SEC']['sec_token'][$tokenName] = array('token' => md5(uniqid(rand(), true)), 'time'  => time(), 'once'  => $once ? true : false);

        else
        {
            // set single use token
            $_SESSION['SEC']['sec_token'][$tokenName]['once'] = $once ? true : false;
            $token                                            = $_SESSION['SEC']['sec_token'][$tokenName]['token'];
        }

        return $token;
    }

    /**
     *
     * @param string $var - explicit variable
     * @param string $selection (p - for POST, s- for SESSION, g - for GET)
     * @return array
     */
    private static function _QbHttpVars2Array($var = '', $selection = 'ps')
    {
        $data = null;
        if ($var)
        {
            if (array_key_exists($var, $_POST) && (strpos(strtolower($selection), 'p') > -1 || !$selection))
                $data = $_POST[$var];
            else if (array_key_exists($var, $_GET) && (strpos(strtolower($selection), 'g') > -1 || !$selection))
                $data = $_GET[$var];
            else if ($_SESSION && array_key_exists($var, $_SESSION) && (strpos(strtolower($selection), 's') > -1 || !$selection))
                $data = $_SESSION[$var];

            if (!isset($data) && function_exists('_QbSpecialParamDelimeter') && array_key_exists($var, self::_QbSpecialParamDelimeter()))
            {
                $data = self::_QbSpecialParamDelimeter();
                $data = $data[$var];
            }
        }
        else
        {
            if (isset($_SESSION) && (strpos(strtolower($selection), 's') > -1 || !$selection))
                $data = $_SESSION;

            if (isset($_GET) && (strpos(strtolower($selection), 'g') > -1 || !$selection))
                $data = $_GET;

            if (isset($_POST) && (strpos(strtolower($selection), 'p') > -1 || !$selection))
                $data = $_POST;

            if (!isset($data) && function_exists('_QbSpecialParamDelimeter'))
                $data = self::_QbSpecialParamDelimeter();
        }

        return $data;
    }

    private static function _QbSpecialParamDelimeter()
    {
        // set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
        $params = array();
        if (strlen(getenv('PATH_INFO')) > 1)
        {
            $GET_array = array();
            $PHP_SELF  = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
            $vars      = explode('/', substr(getenv('PATH_INFO'), 1));
            for ($i = 0, $n = sizeof($vars); $i < $n; $i++)
            {
                if (strpos($vars[$i], '[]'))
                    $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i + 1];
                else
                    $params[$vars[$i]]                     = $vars[$i + 1];
                $i++;
            }

            if (sizeof($GET_array) > 0)
                while (list($key, $value) = each($GET_array))
                    $params[$key] = $value;
        }

        return $params;
    }

    /**
     * Generates unique SALT value to be used with all MD5 hashes.
     * Salt is valid until salt file is removed (normally never)
     */
    private static function _secAppSalt()
    {
        if (!file_exists(self::$_secConfig->_secBaseDir . 'var/app_salt.txt'))
        {
            $applicationSalt = md5(uniqid(rand(), TRUE));
            $logFile         = self::$_secConfig->_secBaseDir . 'var/app_salt.txt';

            file_put_contents($logFile, $applicationSalt);
            chmod($logFile, 0777);

            self::$_secAppSalt = $applicationSalt;
        }
        else
            self::$_secAppSalt = file_get_contents(self::$_secConfig->_secBaseDir . 'var/app_salt.txt');
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
            $logfile = fopen($rootdir . "var/log/log.txt", "a");
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
            chmod($logfile, 0777);
        }
    }

    /**
     * Sets additional security to session data and session cookie.
     * Has to be called after the application fully initiates its session.
     *
     * @return boolean
     */
    private static function _secSecureSession()
    {
        if (!self::$_secConfig->_secSecureSession)
            return FALSE;

        if (!isset($_SESSION))
        {
            self::_secLog('secSecureSession: no SESSION found at execution time. Call secSecureSession after session start.', '');
            return false;
        }

        $sessionData = $_SESSION;

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

        $_SESSION = $sessionData;
    }

    /**
     * Terminates current session and unsets all session content.
     */
    private static function _secTerminateSession($redirectExit = true)
    {
        $seqSessName = self::$_secConfig->_secSessionName ? self::$_secConfig->_secSessionName : session_name();

        // expire cookie
        if (self::$_secConfig->_secSecureCookies && $_COOKIE && isset($_COOKIE[$seqSessName]) && !headers_sent())
        {
            // could we be too early to know 'path' or 'domain' settings?
            $cookieData = session_get_cookie_params();
            setcookie($seqSessName, '', time() - self::$_secConfig->_secSessionLifeTime, $cookieData['path'], $cookieData['domain']);

            if (isset($_SESSION))
                $_COOKIE = array();
        }

        // unset session variables
        if (isset($_SESSION))
            $_SESSION = array();

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

    private static function _secDebug($string = '')
    {
        if (self::$_secConfig->_secDebug)
            echo "<br>------" . $string . "<br>";
    }

    /**
     * Generates data dump of incomming data.
     * Output is to be analysed to design an appropriate SANITIZE - filter
     */
    public static function _secDataDump()
    {
        $datafile = self::$_secConfig->_secBaseDir . "var/dump/app_data.txt";

        if (isset($_GET))
            foreach ($_GET as $param => $value)
                $appdata .= '[GET] ' . $param . '=' . ((is_array($value)) ? self::_secDataDumpRecursive($value, '', 0) : $value) . "\n";

        if (isset($_POST))
            foreach ($_POST as $param => $value)
                $appdata .= '[POST] ' . $param . '=' . ((is_array($value)) ? self::_secDataDumpRecursive($value, '', 0) : $value) . "\n";

        if (isset($_COOKIE))
            foreach ($_COOKIE as $param => $value)
                $appdata .= '[COOKIE] ' . $param . '=' . ((is_array($value)) ? self::_secDataDumpRecursive($value, '', 0) : $value) . "\n";

        if (isset($_SESSION))
            foreach ($_SESSION as $param => $value)
                $appdata .= '[SESSION] ' . $param . '=' . ((is_array($value)) ? self::_secDataDumpRecursive($value, '', 0) : $value) . "\n";

        if (isset($_SERVER))
            foreach ($_SERVER as $param => $value)
                $appdata .= '[SERVER] ' . $param . '=' . ((is_array($value)) ? self::_secDataDumpRecursive($value, '', 0) : $value) . "\n";

        $appdata .= "====================================================================================================\n";

        file_put_contents($datafile, $appdata, FILE_APPEND | LOCK_EX);
        chmod($datafile, 0777);
    }

    /**
     * Retrieve array as string
     *
     * @param array $array
     * @param string $message
     * @param int $level
     * @return string
     */
    private static function _secDataDumpRecursive($array, $message, $level)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                (is_array($array[$key])) ? $recursive = PHP_EOL . self::_secDataDumpRecursive((array) $array[$key], $message, ($level + 1)) : $recursive = $array[$key];
                for ($i = 0; $i <= ($level); $i++)
                    $message .= "\t";
                $message .= "[" . (string) $key . "]" . ' => ' . (string) $recursive . PHP_EOL;
            }
        }
        return $message;
    }

}