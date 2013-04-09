<?php
class Controller_Error
{

    public function index()
    {
        $model = Core::getModel('auth');
        $a = $model->select(1, 'users');
        $b = $a->user_name;
    }
            
}
?>
