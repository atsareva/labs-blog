<?php

require_once 'config.php';

class Db extends Config
{

    private $_host;
    private $_user;
    private $_pass;
    private $_name;
    private $connection;

    function __construct()
    {
        // set parameter for connect with database
        $this->_host = Core::$_config->DB_HOST;
        $this->_user = Core::$_config->DB_USER;
        $this->_pass = Core::$_config->DB_PASS;
        $this->_name = Core::$_config->DB_NAME;
    }

    private function openConnection()
    {
        $this->connection = mysql_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS);
        if (!$this->connection)
        {
            die('Database connection failed: ' . mysql_error());
        }
        else
        {
            $db_select = mysql_select_db($this->DB_NAME);
            if (!$db_select)
            {
                die('Database selection failed: ' . mysql_error());
            }
        }
        mysql_query('set names utf8') or die('set names utf8 failed');
    }

    private function handing($query)
    {
        $return_data = array();
        $count       = 0;

        while ($row = mysql_fetch_array($query))
        {
            if (mysql_num_rows($query) > 1)
            {
                foreach ($row as $key => $value)
                {
                    if (is_string($key))
                        $return_data[$count][$key] = $value;
                }
                $count++;
            }
            else
            {
                foreach ($row as $key => $value)
                {
                    if (is_string($key))
                        $return_data[$key] = $value;
                }
            }
        }
        return $return_data;
    }

    public function sql($query)
    {

        $this->openConnection();
        $result = mysql_query($query, $this->connection);
        if (!$result)
        {
            die('Database query failed: ' . mysql_error());
        }
        if (!is_bool($result))
            $result = $this->handing($result);
        return $result;
    }

}

?>
