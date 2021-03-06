<?php

require_once CORE_PATH . 'controller/controller' . EXT;
require_once CORE_PATH . 'view/view_admin' . EXT;

class Controller_A extends Controller
{

    /**
     * View instance
     *
     * @var object
     */
    public $_view;

    public function __construct()
    {
        $this->_view = new View_Admin();

        $user = Core::getHelper('user')->getUserInfo();
        if (!$user || !$user->getId() || $user->getAccessId() < 3)
            $this->redirect('auth/login');
    }

}

?>
