<?php

$path = dirname(__FILE__);
define('EXT', '.php');

define('CLER', 'Controller_');
define('ML', 'Model_');
define('HR', 'Helper_');

$core = 'core';

$controllers = 'controllers';
$views = 'views';
$models = 'models';
$helper = 'helper';
$config = 'config';

$install = 'install';


define('CORE_PATH', $path . DIRECTORY_SEPARATOR . $core . DIRECTORY_SEPARATOR);

define('CPATH', $path . DIRECTORY_SEPARATOR . $controllers . DIRECTORY_SEPARATOR);
define('VPATH', $path . DIRECTORY_SEPARATOR . $views . DIRECTORY_SEPARATOR);
define('MPATH', $path . DIRECTORY_SEPARATOR . $models . DIRECTORY_SEPARATOR);
define('HPATH', $path . DIRECTORY_SEPARATOR . $helper . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', $path . DIRECTORY_SEPARATOR . $config . DIRECTORY_SEPARATOR);

define('IPATH', $path . DIRECTORY_SEPARATOR . $install . DIRECTORY_SEPARATOR);

set_include_path(get_include_path() . PATH_SEPARATOR . CPATH . PATH_SEPARATOR . VPATH . PATH_SEPARATOR . MPATH . PATH_SEPARATOR . CONFIG_PATH . PATH_SEPARATOR . CORE_PATH);

// if isset directory setup run it
if (!is_file(CONFIG_PATH . 'config' . EXT))
{
    if (isset($_POST['remove']) && $_POST['remove'] == '1')
    {
        RemoveDir(IPATH);
        unset($_SESSION['site_name']);
        unset($_SESSION['db']);
    }
    require_once IPATH . 'install' . EXT;
    exit();
}

session_start();

require_once CORE_PATH . 'core' . EXT;
Core::run();
mysql_close();

function RemoveDir($path)
{
    if (file_exists($path) && is_dir($path))
    {
        $dirHandle = opendir($path);
        while (false !== ($file = readdir($dirHandle)))
        {
            if ($file != '.' && $file != '..')// исключаем папки с назварием '.' и '..' 
            {
                $tmpPath = $path . '/' . $file;
                chmod($tmpPath, 0777);

                if (is_dir($tmpPath))
                {  // если папка
                    RemoveDir($tmpPath);
                }
                else
                {
                    if (file_exists($tmpPath))
                    {
                        // удаляем файл 
                        unlink($tmpPath);
                    }
                }
            }
        }
        closedir($dirHandle);

        // удаляем текущую папку
        if (file_exists($path))
        {
            rmdir($path);
        }
    }
    else
    {
        echo "Удаляемой папки не существует или это файл!";
    }
}
?>

