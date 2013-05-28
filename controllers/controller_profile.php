<?php

require_once CORE_PATH . 'controller/controller_front' . EXT;

class Controller_Profile extends Controller_Front
{

    function index($id)
    {
        if (!$this->checkSessionUser())
            $this->redirect('profile/login');

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
            Security::secCheckToken('userLogin');
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
        $error = '';
        /**
         * @todo Realise validation for post
         */
        if (isset($_POST) && !empty($_POST))
        {
            session_regenerate_id();
            $data = $_POST;
            unset($_POST);

            /**
             * @todo Realise generate salt for password
             */
            $data['pass']          = md5($data['pass']);
            $data['register_date'] = time();
            $data['last_login']    = time();
            $data['access_id']     = 2;
            $data['session_id']    = session_id();
            unset($data['confirm_pass']);

            $user = Core::getModel('user')
                    ->setData($data)
                    ->save();
            $this->redirect('profile/index/' . $user->getId());
        }

        $faculties   = Core::getModel('faculty')->getCollection()->getData();
        $departments = Core::getModel('department')->getCollection()->getData();
        $statuses    = Core::getModel('user_status')
                ->addFieldToFilter('name', array(array('=' => 'студент'), array('=' => 'преподаватель')))
                ->getCollection()
                ->getData();

        $this->_view->setTitle('Регистрация')
                ->setBaseClass('signup')
                ->setChild('content', 'front/profile/signup', array('error'       => $error, 'faculties'   => $faculties, 'departments' => $departments, 'statuses'    => $statuses));
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
