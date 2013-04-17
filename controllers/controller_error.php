<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Error extends Controller
{

    public function index()
    {
        $view = new View();
        $view->setTemplate('page/one-column')
                ->setTitle('404')
                ->setChild('content', 'page/html/404');
    }

}

?>
