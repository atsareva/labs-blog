<?php

require_once CORE_PATH . 'controller/controller_front' . EXT;

class Controller_Error extends Controller_Front
{

    public function index()
    {
        $this->_view->setTemplate('front/page/one-column')
                ->setTitle('404')
                ->setChild('content', 'front/page/html/404');
    }

}

?>
