<?php

require_once CORE_PATH . 'controller/controller' . EXT;

class Controller_Profile extends Controller
{

    function index($id)
    {
        if (!$this->checkSessionUser())
            $this->redirect('home');

        $data = array();
        if (isset($id[0]))
        {
            $user = Core::getModel('user')->load((int) $id[0]);
            if ($user->getId())
            {
                $data = array(
                    'user'       => $user,
                    'faculty'    => Core::getModel('faculty')->load($user->getFacultyId()),
                    'department' => Core::getModel('department')->load($user->getDepartmentId()),
                    'userStatus' => Core::getModel('user_status')->load($user->getStatusId())
                );
            }
        }

        $this->_view->setTitle('Профайл')
                ->setChild('content', 'front/profile/profile', $data);
    }

    function login()
    {
        $error     = '';
        $userModel = Core::getModel('user');

        if ($this->checkSessionUser())
            $this->redirect('home');

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
                            ->setData('session_id', session_id())
                            ->save();
                    $this->redirect('home');
                }
            }
            else
            {
                $error = "Проверьте правильность введенных логина и пароля.";
            }
        }

        $this->_view->setTitle('Вход')
                ->setBaseClass('login')
                ->setChild('content', 'front/profile/login', array('error' => $error));
    }

    function signup()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'user_name'     => $_POST['login'],
                'email'         => $_POST['email'],
                'pass'          => md5($_POST['pass']),
                'register_date' => time(),
                'last_login'    => time(),
                'access_id'     => 2
            );

            $this->insert($data, 'users');
            $this->redirect('/profile/login');
        }

        $array      = $this->front_menu();
        $menu_name  = $array[0];
        $items_menu = $array[1];

        $title = 'Регистрация';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/signup.php';
        require 'front/footer.php';
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

    private function checkSessionUser()
    {
        $userModel = Core::getModel('user');

        $id = Core::getHelper('user')->getCurrentUser();
        if ($id)
        {
            session_regenerate_id();
            $userModel->load($id)
                    ->setData('session_id', session_id())
                    ->save();
            return true;
        }
        return false;
    }

}

?>
