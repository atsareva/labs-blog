<?php

//require_once 'core' . EXT;
require_once 'helper' . EXT;

class Install
{

    public function __construct()
    {
        
    }

    
    
    public function step1()
    {
        $php_version = Helper::check_json_support();
        $xml_support = Helper::check_xml_support();
        $mysql_support = Helper::check_mysql_support();
        $json_support = Helper::check_json_support();
        $ini_parser = Helper::getIniParserAvailability();
        $config_file = Helper::config_file();

        $hover_step1 = TRUE;
        require 'install/views/head.php';
        require 'install/views/step1.php';
    }

    public function step2()
    {
        $hover_step2 = TRUE;
        require 'install/views/head.php';
        require 'install/views/step2.php';
    }

    public function step3($_POST)
    {
        $helper = new Helper();
        $response = $helper->check_database($_POST);

        if (!$response)
        {
            die('Something has happened. Try again.');
        }

        $hover_step3 = TRUE;
        require 'install/views/head.php';
        require 'install/views/step3.php';
    }

    public function step4()
    {
        $hover_step4 = TRUE;

        require 'install/views/head.php';
        require 'install/views/step4.php';
    }

}

session_start();
//header("Location: /installation/simplecms", TRUE, '302');

$obj = new Install();
if (isset($_POST['reload']))
{
    require_once 'ajax' . EXT;
    exit();
}
if (isset($_GET['configuration']))
{

    if (isset($_POST) && !empty($_POST))
    {
        if (isset($_POST['check_database']) && $_POST['check_database'] == '1')
        {
                $obj->step3($_POST);
        }
        if (isset($_POST['create_database']) && $_POST['create_database'] == '1')
        {
            if (isset($_SESSION['db']) && !empty($_SESSION['db']))
            {
                $helper = new Helper();
                $response = $helper->create_database($_POST);
                if (isset($_SESSION['db'], $_SESSION['site_name'], $_SESSION['users']) && !empty($_SESSION['db']) && !empty($_SESSION['site_name']) && !empty($_SESSION['users']))
                {
                    $obj->step4();
                    unset($_SESSION['site_name']);
                    unset($_SESSION['db']);
                }
                else
                {
                    header('Location: http://popsa.loc');
                }
            }
            else
            {
                header('Location: http://popsa.loc');
            }
        }
    }
    else
    {
        $obj->step2();
    }
}
else
{
    $obj->step1();
}


/*

  if (isset($_POST) && !empty($_POST))
  {
  $obj->step2();
  }
  else
  {
  $obj->step1();
  }
 * *
 */
//$obj->step2();
require 'install/views/footer.php';
?>
