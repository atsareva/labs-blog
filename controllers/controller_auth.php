<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Auth extends Controller_A
{

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

    public function signup()
    {
        echo "signup";
    }

    public function forgotpass()
    {
        echo "forgotpass";
    }

    public function change_profile($data = NULL)
    {
        if ($data != NULL)
        {
            if (empty($data['pass']) || empty($data['confirm_pass']))
            {
                $where  = array(
                    'id' => $_SESSION['user']['id']
                );
                $result = $this->select($where, 'users');

                $data['pass'] = $result['pass'];
            }
            else
            {
                $data['pass']  = md5($data['pass']);
            }
            $array         = array(
                'id'            => $_SESSION['user']['id'],
                'user_name'     => $data['login'],
                'full_name'     => $data['full_name'],
                'pass'          => $data['pass'],
                'email'         => $data['email'],
                'faculty_id'    => $data['faculty_id'],
                'department_id' => $data['department_id'],
                'status_id'     => $data['status_id'],
                'photo'         => $data['attachment']
            );
            $result_update = $this->update($array, 'users');

            if ($result_update == TRUE)
            {
                $where  = array(
                    'id' => $_SESSION['user']['id']
                );
                $result = $this->select($where, 'users');
                return $result;
            }
            else
            {
                $result['error'] = "Something error. Try again";
                return $result;
            }
        }
        else
        {
            $where  = array(
                'id' => $_SESSION['user']['id']
            );
            $result = $this->select($_SESSION['user']['id'], 'users');
            //var_dump($result);die();
        }

        return $result;
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
