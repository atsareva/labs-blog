<?php

require_once CPATH . 'controller_profile' . EXT;

class Controller_Auth extends Controller_Profile
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
                ->setBaseClass('login')
                ->setChild('content', 'admin/profile/login', array('error' => $error));


//        if (isset($_POST['login']) && isset($_POST['pass']))
//        {
//            $where  = array(
//                'user_name' => "{$_POST['login']}",
//                'pass'      => md5($_POST['pass'])
//            );
//            $result = $this->select($where, 'users');
//            if (!empty($result))
//            {
//                $_SESSION['user'] = array(
//                    'id'        => $result['id'],
//                    'user_name' => $result['user_name'],
//                    'access_id' => $result['access_id']
//                );
//
//                $data = array(
//                    'id'         => $result['id'],
//                    'last_login' => time()
//                );
//
//                $this->update($data, 'users');
//
//                header('Location: http://' . $this->BASE_URL . '/admin');
//            }
//        }
//        $title = 'Авторизация';
//        require 'head.php';
//        require 'login.php';
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

}

?>
