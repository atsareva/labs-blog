<?php

require_once CORE_PATH . 'controller/controller' . EXT;
require_once CORE_PATH . 'view/view_front' . EXT;

class Controller_Front extends Controller
{

    /**
     * View instance
     *
     * @var object
     */
    public $_view;

    public function __construct()
    {
        $this->_view = new View_Front();
    }

}

?>
