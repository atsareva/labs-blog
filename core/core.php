<?php

require_once CONFIG_PATH . 'config' . EXT;

class Core
{

    static private $_path = array(
        CLER => CPATH,
        ML   => MPATH,
        HR   => HPATH
    );

    public static function run()
    {
        // create object by requested uri
        self::autoLoad();
    }

    private static function autoLoad()
    {
        $class  = Config::$_defaultController;
        $method = NULL;
        $param  = array();

        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/')
        {
            $requestUri = $_SERVER['REQUEST_URI'];
            if ($strpos     = strpos($requestUri, '?'))
                $requestUri = substr($requestUri, 0, $strpos);

            $requestedPathArray = explode('/', $requestUri);
            foreach ($requestedPathArray as $key => $value)
            {
                switch ($key)
                {
                    case 0:
                        break;
                    case 1:
                        $class   = strtolower($value);
                        break;
                    case 2:
                        $method  = strtolower($value);
                        break;
                    default:
                        $param[] = $value;
                        break;
                }
            }

            if (!self::findFile($class, CLER))
                $class = 'error';
        }

        require_once strtolower(CPATH . CLER . $class . EXT);
        $className = CLER . ucwords($class);
        $instance  = new $className();
        (method_exists($instance, $method)) ? $instance->$method($param) : $instance->index();
    }

    /**
     * Retrieve model object by it name
     *
     * @param string $className
     * @return object|boolean
     */
    public static function getModel($className)
    {
        if (self::findFile($className, ML))
            return self::getClassObject($className, ML);
        return FALSE;
    }

    /**
     * Retrieve helper object by it name
     *
     * @param type $className
     * @return object|boolean
     */
    public static function getHelper($className)
    {
        if (self::findFile($className, HR))
            return self::getClassObject($className, HR);
        return FALSE;
    }

    /**
     * Include request class and retrieve it object
     *
     * @param string $className
     * @param string $type
     * @return object
     */
    private static function getClassObject($className, $type)
    {
        require_once strtolower(self::$_path[$type] . $type . $className . EXT);
        $className = $type . ucwords($className);
        return new $className();
    }

    /**
     * Find file by name
     *
     * @param string $class
     * @return boolean
     */
    private static function findFile($class, $type)
    {
        $class = strtolower($type . $class . EXT);
        if (is_file(self::$_path[$type] . $class))
            return TRUE;
        return FALSE;
    }

    /**
     * Write message to system.log
     *
     * @param mix $message
     * @param string $path
     */
    public static function log($message, $path = NULL)
    {
        $typeMessage = gettype($message);
        if (is_object($message))
        {
            $olo     = (array) $message;
            $message = (array) $message;
        }
        if (is_array($message))
        {
            $typeMessage .= '(' . count($message) . ')';
            $message = PHP_EOL . self::_logVarDump((array) $message, '', 0);
        }
        $logDir  = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'var';
        $logFile = $logDir . DIRECTORY_SEPARATOR . 'system.log';
        if (!file_exists($logFile))
        {
            file_put_contents($logFile, '');
            chmod($logFile, 0777);
        }
        $message = '[ ' . date("Y-m-d H:i:s") . ' ] ' . $typeMessage . ' ' . $message;
        ($path) ? $message .= ' in ' . $path . PHP_EOL : $message .= PHP_EOL;
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * Retrieve array as string
     *
     * @param array $array
     * @param string $message
     * @param int $level
     * @return string
     */
    private static function _logVarDump($array, $message, $level)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                (is_array($array[$key])) ? $recursive = PHP_EOL . self::_logVarDump((array) $array[$key], $message, ($level + 1)) : $recursive = $array[$key];
                for ($i = 0; $i <= ($level); $i++)
                    $message .= "\t";
                $message .= "[" . (string) $key . "]" . ' => ' . (string) $recursive . PHP_EOL;
            }
        }
        return $message;
    }

    /**
     * Get site base url
     *
     * @return string
     */
    public static function getBaseUrl()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol . Config::$_baseUrl;
    }

    public static function getSession()
    {
        require_once CORE_PATH . 'session' . EXT;
        return new Session();
    }

}

?>
