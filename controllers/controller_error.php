<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Error extends Controller
{

    public function index()
    {
        $model = Core::getModel('auth');
        $model->setData(array('user_name' => 'test', 'email' => time()), 'users')
                ->save();

        $model->load(1)
                ->setUserName('admin')
                ->save();
        echo $model->getUserName();
    }

}

?>
