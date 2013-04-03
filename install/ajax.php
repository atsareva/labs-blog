<?php
header('Content-Type: application/html; charset=utf-8');

class Ajax
{

    public function reload()
    {
        $php_version = Helper::check_json_support();
        $xml_support = Helper::check_xml_support();
        $mysql_support = Helper::check_mysql_support();
        $json_support = Helper::check_json_support();
        $ini_parser = Helper::getIniParserAvailability();
        $config_file = Helper::config_file();


        require 'install/views/step1.php';
    }

}

$obj = new Ajax();

if (isset($_POST['reload']))
{
    $obj->reload();
}

?>