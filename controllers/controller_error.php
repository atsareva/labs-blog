<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Error extends Controller
{

    public function index()
    {
        //Core::log($_SERVER, dirname(__FILE__));
        if ($_SERVER['REQUEST_URI'] != '/favicon.ico')
        {
        $model = Core::getModel('auth');
        $model->setData(array('user_name' => 'test', 'email' => time(), 'olo' => 'olo'))
                ->save();
//        $model->load(190)
//                ->setUserName('oloo')
//                ->save();
        }
//        $model->load(1)
//                ->setUserName('admin')
//                ->save();
        echo $model->getUserName();
    }

}

?>
