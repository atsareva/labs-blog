<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Auth extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $error     = '';
        $userModel = Core::getModel('user');

        $userAccessId = $this->checkSessionUser();
        if ($userAccessId && $userAccessId > 2)
            $this->redirect('admin');

        if (isset($_POST['login']) && isset($_POST['pass']))
        {
            $user = $userModel->addFieldToFilter('user_name', array('=' => $_POST['login']))
                    ->addFieldToFilter('pass', array('=' => md5($_POST['pass'])))
                    ->getCollection()
                    ->getData();
            if (isset($user[0]->id))
            {
                if ($user[0]->block == 1)
                    $error = "Вас заблокировал администратор сайта.";
                else
                {
                    $userModel->load($user[0]->id)
                            ->setData(array('session_id' => session_id(), 'last_login' => time()))
                            ->save();
                    $this->redirect('admin');
                }
            }
            else
            {
                $error = "Проверьте правильность введенных логина и пароля.";
            }
        }

        $this->_view->setTitle('Авторизация')
                ->setTemplate('admin/profile/login', array('error' => $error))
                ->setBaseClass('login');
    }

    public function logout()
    {
        $userModel = Core::getModel('user');

        $id = Core::getHelper('user')->getCurrentUser();
        if ($id)
            $userModel->load($id)
                    ->setData('session_id', NULL)
                    ->save();
        $this->redirect('home');
    }

    public function profile()
    {
        $error = '';
        $user  = Core::getModel('user')->load(Core::getHelper('user')->getCurrentUser());

        if (isset($_POST) && !empty($_POST))
        {
            /**
             * @todo Realise validation for post
             */
            (isset($_POST['save_exit']) && $_POST['save_exit'] == 1) ? $saveExit = true : $saveExit = false;

            $data = $_POST;
            unset($_POST);

            if (!empty($data['pass']) && !empty($data['confirm_pass']))
                $data['pass'] = md5($data['pass']);

            unset($data['confirm_pass'], $data['save_exit']);

            $user->setData($data)->save();
            if ($saveExit)
                $this->redirect('admin');
        }

        $faculties   = Core::getModel('faculty')->getCollection()->getData();
        $departments = Core::getModel('department')->getCollection()->getData();
        $statuses    = Core::getModel('user_status')->getCollection()->getData();

        $this->_view->setTitle('Мой профиль')
                ->setBaseClass('profile')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuProfile' => true))
                ->setChild('content', 'admin/profile/profile', array('error'       => $error, 'user' => $user,'faculties'   => $faculties, 'departments' => $departments, 'statuses'    => $statuses));
    }

    public function signup()
    {
        echo "signup";
    }

    public function forgotpass()
    {
        echo "forgotpass";
    }

    

    public function load_user($id)
    {
        if (!is_array($data))
        {
            $where = array(
                'id' => $id
            );

            $result = $this->select($where, 'users');
        }

        return $result;
    }

    protected function checkSessionUser()
    {
        $userModel = Core::getModel('user');

        $id = Core::getHelper('user')->getCurrentUser();
        if ($id)
        {
            session_regenerate_id();
            $userModel->load($id)
                    ->setData('session_id', session_id())
                    ->save();
            return $userModel->getAccessId();
        }
        return false;
    }

}

?>
