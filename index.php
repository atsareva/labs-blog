<?php

$path = dirname(__FILE__);
define('EXT', '.php');

$controllers = 'controllers';
$views = 'views';
$models = 'models';
$config = 'config';

$install = 'install';



//define('PATH', realpath($controllers).DIRECTORY_SEPARATOR);

define('CPATH', $path . DIRECTORY_SEPARATOR . $controllers . DIRECTORY_SEPARATOR);
define('VPATH', $path . DIRECTORY_SEPARATOR . $views . DIRECTORY_SEPARATOR);
define('MPATH', $path . DIRECTORY_SEPARATOR . $models . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', $path . DIRECTORY_SEPARATOR . $config . DIRECTORY_SEPARATOR);

define('IPATH', $path . DIRECTORY_SEPARATOR . $install . DIRECTORY_SEPARATOR);


//chmod(IPATH, 0755);
//die();
set_include_path(get_include_path() . PATH_SEPARATOR . CPATH . PATH_SEPARATOR . VPATH . PATH_SEPARATOR . MPATH . PATH_SEPARATOR . CONFIG_PATH);




$install = $path . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR;
//die();

if (is_file($install . 'install' . EXT))
{
    if (isset($_POST['remove']) && $_POST['remove'] == '1')
    {
        RemoveDir(IPATH);
        unset($_SESSION['site_name']);
        unset($_SESSION['db']);
    }
    require_once $install . 'install' . EXT;
    //var_dump($_SERVER['REQUEST_URI']);die();

    exit();
}

session_start();
require_once 'core' . EXT;

Core::auto_load();

//$class=new Core;
//$class->auoto_load();
//ini_set('unserialize_callback_func', 'spl_autoload_call');

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

