<?php

class Controller_Error
{

    public function index()
    {
        $model = Core::getModel('auth');
        $a     = $model
                ->addFieldToFilter('user_name', array(
                    array('=' => 'user'),
                    array('=' => 'admin'))
                )
                ->getCollection();
        //echo $a->user_name;
    }

}

?>
