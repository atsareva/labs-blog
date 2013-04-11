<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Error extends Controller
{

    public function index()
    {
        $model = Core::getModel('auth');
        $model->setData(array('user_name' => 'OLO', 'email' => 'ololo', 'pass' => '1234'))
                ->save();
        echo $model->_lastInsertId;

//        $model->load(1)
//                ->setUserName('admin')
//                ->save();
        //echo $a->user_name;
//        $_SESSION['"' . time() . '"'] = time();
        var_dump($_SESSION);
//        unset($_SESSION);
    }

}

?>
