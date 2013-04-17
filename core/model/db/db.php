<?php

require_once CONFIG_PATH . 'config' . EXT;

class Db extends Config
{

    private static $_instance;
    private $_connection;
    private $_config = array();

    private function _setConfig()
    {
        // set parameter for connect with database
        $this->_config = array(
            'dbHost' => self::$_dbHost,
            'dbUser' => self::$_dbUser,
            'dbPass' => self::$_dbPass,
            'dbName' => self::$_dbName,
        );
    }

    private function openConnection()
    {
        if (empty($this->_config))
            $this->_setConfig();
        $this->_connection = mysql_connect($this->_config['dbHost'], $this->_config['dbUser'], $this->_config['dbPass']);
        if (!$this->_connection)
            die('Database connection failed: ' . mysql_error());
        else
        {
            if (!mysql_select_db($this->_config['dbName']))
                die('Database selection failed: ' . mysql_error());
        }
        mysql_query('set names utf8') or die('set names utf8 failed');
    }

    private function handing($query)
    {
        $returnData = array();
        $count      = 0;

        while ($row = mysql_fetch_array($query))
        {
            if (mysql_num_rows($query) > 1)
            {
                foreach ($row as $key => $value)
                {
                    if (is_string($key))
                        $returnData[$count][$key] = $value;
                }
                $count++;
            }
            else
            {
                foreach ($row as $key => $value)
                {
                    if (is_string($key))
                        $returnData[$key] = $value;
                }
            }
        }
        return $returnData;
    }

    public function sql($query)
    {
        $this->openConnection();
        $result = mysql_query($query, $this->_connection);
        if (!$result)
            die('Database query failed: ' . mysql_error());
        if (!is_bool($result))
            $result = $this->handing($result);
        return $result;
    }

    public static function getInstance()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

?>
