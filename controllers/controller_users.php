<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Users extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        /**
         * @todo Validation
         */
        $this->_checkAccess();
        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'user_name'     => $_POST['user_name'],
                'pass'          => md5($_POST['pass']),
                'email'         => $_POST['email'],
                'register_date' => time(),
                'status_id'     => $_POST['status_id'],
                'access_id'     => $_POST['access_id']
            );

            $user = Core::getModel('user')->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/users');
        }

        $head    = 'Создать пользователя';
        $userUrl = Core::getBaseUrl() . 'users/create';

        $statuses = Core::getModel('user_status')->getCollection()->getData();
        $access   = Core::getModel('access')->getAccess();

        $this->_view->setTitle('Создать пользователя')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('$menuUsers' => true))
                ->setChild('content', 'admin/user/form', array('head'     => $head, 'userUrl'  => $userUrl, 'statuses' => $statuses, 'access'   => $access));
    }

    public function edit($id)
    {
        /**
         * @todo Validation
         */
        $this->_checkAccess();
        $user = Core::getModel('user')->load((int) $id[0]);

        if ($user->getId() && isset($_POST) && !empty($_POST))
        {
            $data = array(
                'user_name'     => $_POST['user_name'],
                'email'         => $_POST['email'],
                'register_date' => time(),
                'status_id'     => $_POST['status_id'],
                'access_id'     => $_POST['access_id']
            );

            if (!empty($data['pass']) && !empty($data['confirm_pass']))
                $data['pass'] = md5($data['pass']);

            $user = Core::getModel('user')->load($user->getId())->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/users');
        }

        $head    = 'Редактировать пользователя';
        $userUrl = Core::getBaseUrl() . 'users/edit/' . $user->getId();

        $statuses = Core::getModel('user_status')->getCollection()->getData();
        $access   = Core::getModel('access')->getAccess();

        $this->_view->setTitle('Редактировать пользователя')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('$menuUsers' => true))
                ->setChild('content', 'admin/user/form', array('head'     => $head, 'user'     => $user, 'userUrl'  => $userUrl, 'statuses' => $statuses, 'access'   => $access));
    }

    public function remove()
    {
        $this->_checkAccess();
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('user')->load($id)->unsetData()->save();
    }

    private function _checkAccess()
    {
        $user = Core::getHelper('user')->getUserInfo();
        if ($user->getAccessId() < 4)
            $this->redirect('admin');
    }

}

?>
