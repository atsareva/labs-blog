<?php

class Controller_Error
{

    public function index()
    {
        $model = Core::getModel('auth');
        $a     = $model->addFieldToFilter('user_name')->addFieldToFilter('user_name', array('=' => 'admin', '=' => 'user'))->getCollection();
        //echo $a->user_name;
    }

}

?>
