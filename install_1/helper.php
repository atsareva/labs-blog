<?php

class Helper
{

    private $connection;

    public static function check_version()
    {
        $real_version = phpversion();
        $version = explode('-', $real_version);
        $version = str_replace('.', '', $version);
        $php_version = (int) $version[0];
        if ($php_version > 524)
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function check_xml_support()
    {
        if (extension_loaded('xml'))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function check_mysql_support()
    {
        if (function_exists('mysql_connect'))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function check_json_support()
    {
        if (function_exists('json_encode') && function_exists('json_decode'))
        {
            return TRUE;
        }
        return FALSE;
    }

    public static function getIniParserAvailability()
    {
        $disabled_functions = ini_get('disable_functions');

        if (!empty($disabled_functions))
        {
            // Attempt to detect them in the disable_functions black list
            $disabled_functions = explode(',', trim($disabled_functions));
            $number_of_disabled_functions = count($disabled_functions);

            for ($i = 0; $i < $number_of_disabled_functions; $i++)
            {
                $disabled_functions[$i] = trim($disabled_functions[$i]);
            }

            if (phpversion() >= '5.3.0')
            {
                $result = !in_array('parse_ini_string', $disabled_functions);
            }
            else
            {
                $result = !in_array('parse_ini_file', $disabled_functions);
            }
        }
        else
        {

            // Attempt to detect their existence; even pure PHP implementation of them will trigger a positive response, though.
            if (phpversion() >= '5.3.0')
            {
                $result = function_exists('parse_ini_string');
            }
            else
            {
                $result = function_exists('parse_ini_file');
            }
        }

        return $result;
    }

    public static function config_file()
    {
        if (is_writable('../config/config.php') || (!file_exists('../config/config.php') && is_writable('../config')))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function check_database($post)
    {
        if (!empty($_POST['server_name']) && !empty($_POST['user_name']) && !empty($_POST['pass']) && !empty($_POST['database_name']))
        {
            $response = $this->open_connection($_POST['server_name'], $_POST['user_name'], $_POST['pass'], $_POST['database_name']);

            if ($response)
            {
                $_SESSION['db'] = array(
                    'db_host' => $_POST['server_name'],
                    'db_user' => $_POST['user_name'],
                    'db_pass' => $_POST['pass'],
                    'db_name' => $_POST['database_name']
                );

                return TRUE;
            }
            return FALSE;
        }
        else
        {
            return FALSE;
        }
    }

    public function create_database($post)
    {
        if (!empty($_POST['site_name']) && !empty($_POST['user_email']) && !empty($_POST['login']) && !empty($_POST['pass']))
        {
            /**
             * create database with all tables
             */
            $path = dirname(__FILE__);

            $fh = fopen("{$path}/database.sql", "r") or die("Can't open file!");

            $file = fread($fh, filesize("{$path}/database.sql"));

            $queries = $this->_splitQueries($file);

            $this->sql($queries);

            fclose($fh);

            /**
             * Insert data of Super User to database  
             */
            $pass = md5($_POST['pass']);
            $date = time();

            $sql = "INSERT INTO users (user_name, email, pass, register_date, last_login, access_id) VALUES ('{$_POST['login']}', '{$_POST['user_email']}', '{$pass}', '{$date}', '{$date}', '5')";
            $this->sql($sql);
   
            $sql = "SELECT MAX(LAST_INSERT_ID( id )) FROM users LIMIT 1";
            $result = $this->sql($sql);

            $_SESSION['site_name'] = $_POST['site_name'];
            $_SESSION['users'] = array(
                'id' => 1,
                'user_email' => $_POST['user_email'],
            );
            
             $sql="INSERT INTO settings (site_name, db_host, db_name, db_user) VALUES ('{$_POST['site_name']}', '{$_SESSION['db']['db_host']}', '{$_SESSION['db']['db_name']}', '{$_SESSION['db']['db_user']}')";
             $this->sql($sql);
             
        }
    }

    private function open_connection($db_host, $db_user, $db_pass, $db_name)
    {
        $this->connection = mysql_connect($db_host, $db_user, $db_pass);
        if (!$this->connection)
        {
            die('Database connection failed: ' . mysql_error());
        }
        else
        {
            $db_select = mysql_select_db($db_name);
            if (!$db_select)
            {
                die('Database selection failed: ' . mysql_error());
            }
        }
        return TRUE;
    }

    public function sql($sql)
    {

        $this->open_connection($_SESSION['db']['db_host'], $_SESSION['db']['db_user'], $_SESSION['db']['db_pass'], $_SESSION['db']['db_name']);

        if (is_array($sql))
        {
            foreach ($sql as $query)
            {
                $result = mysql_query($query, $this->connection);
                if (!$result)
                {
                    die('Database query failed: ' . mysql_error());
                }
                if (!is_bool($result))
                {
                    return $result;
                }
            }
        }
        else
        {
            $result = mysql_query($sql, $this->connection);
            if (!$result)
            {
                die('Database query failed: ' . mysql_error());
            }
            if (!is_bool($result))
            {
                return $result;
            }
        }
    }

    function _splitQueries($sql)
    {
        // Initialise variables.
        $buffer = array();
        $queries = array();
        $in_string = false;

        // Trim any whitespace.
        $sql = trim($sql);

        // Remove comment lines.
        $sql = preg_replace("/\n\#[^\n]*/", '', "\n" . $sql);

        // Parse the schema file to break up queries.
        for ($i = 0; $i < strlen($sql) - 1; $i++)
        {
            if ($sql[$i] == ";" && !$in_string)
            {
                $queries[] = substr($sql, 0, $i);
                $sql = substr($sql, $i + 1);
                $i = 0;
            }

            if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\")
            {
                $in_string = false;
            }
            elseif (!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\"))
            {
                $in_string = $sql[$i];
            }
            if (isset($buffer[1]))
            {
                $buffer[0] = $buffer[1];
            }
            $buffer[1] = $sql[$i];
        }

        // If the is anything left over, add it to the queries.
        if (!empty($sql))
        {
            $queries[] = $sql;
        }

        return $queries;
    }

}

?>
