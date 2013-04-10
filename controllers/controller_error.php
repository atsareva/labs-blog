<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Error extends Controller
{

    public function index()
    {
        $model = Core::getModel('auth');
        $a     = $model->load(1);
        $model->ololo('aaaaaaaa');
        //echo $a->user_name;
    }

}

?>
