<?php

require 'model.php';

class Controller_Auth extends Model
{

    function __construct()
    {
        parent::__construct();
        //echo "in";
    }

    // public function index()
    //{
    //}

    public function login()
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user']['access_id'] > 2)
        {
            header('Location: http://' . $this->BASE_URL . '/admin');
        }
        if (isset($_POST['login']) && isset($_POST['pass']))
        {
            $where = array(
                'user_name' => "{$_POST['login']}",
                'pass' => md5($_POST['pass'])
            );
            $result = $this->select($where, 'users');
            if (!empty($result))
            {
                $_SESSION['user'] = array(
                    'id' => $result['id'],
                    'user_name' => $result['user_name'],
                    'access_id' => $result['access_id'] 
                );

                $data = array(
                    'id' => $result['id'],
                    'last_login' => time()
                );

                $this->update($data, 'users');

                header('Location: http://' . $this->BASE_URL . '/admin');
            }
        }
        $title = 'Авторизация';
        require 'head.php';
        require 'login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: http://' . $this->BASE_URL . '/auth/login');
    }

    public function signup()
    {
        echo "signup";
    }

    public function forgotpass()
    {
        echo "forgotpass";
    }

    public function change_profile($data=NULL)
    {
        if ($data != NULL)
        {
            if (empty($data['pass']) || empty($data['confirm_pass']))
            {
                $where = array(
                    'id' => $_SESSION['user']['id']
                );
                $result = $this->select($where, 'users');

                $data['pass'] = $result['pass'];
            }
            else
            {
                $data['pass'] = md5($data['pass']);
            }
            $array = array(
                'id' => $_SESSION['user']['id'],
                'user_name' => $data['login'],
                'full_name' => $data['full_name'],
                'pass' => $data['pass'],
                'email' => $data['email'],
                'faculty_id' => $data['faculty_id'],
                'department_id' => $data['department_id'],
                'status_id' => $data['status_id'],
                'photo' => $data['attachment']
            );
            $result_update = $this->update($array, 'users');

            if ($result_update == TRUE)
            {
                $where = array(
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
            $where = array(
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
