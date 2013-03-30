<?php

require_once 'model.php';

class Controller_Profile extends Model
{

    function index()
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header('Location: http://' . $this->BASE_URL . '/profile/login');
        }

        if (isset($_GET['id']))
        {
            $where = array(
                'id' => $_GET['id']
            );
            $user = $this->select($where, 'users');

            $where = array(
                'id' => $user['faculty_id']
            );
            $faculty = $this->select($where, 'faculties');

            $where = array(
                'id' => $user['department_id']
            );
            $department = $this->select($where, 'departments');

            $where = array(
                'id' => $user['status_id']
            );
            $user_status = $this->select($where, 'user_status');
        }

        $array = $this->front_menu();
        $menu_name = $array[0];
        $items_menu = $array[1];

        $title = 'Профайл';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/profile.php';
        require 'front/footer.php';
    }

    function my_profile()
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header('Location: http://' . $this->BASE_URL . '/profile/login');
        }
    }

    function login()
    {
        //session_destroy();
        if (isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $this->redirect('/home/front');
        }
        
        if (isset($_POST['login']) && isset ($_POST['pass']))
        {
            $where = array(
                'user_name' => $_POST['login'],
                'pass' => md5($_POST['pass'])
            );
            
            $user = $this->select($where, 'users');
            if(!empty ($user) && $user['block'] == 1)
            {
                $error = "Вас заблокировал администратор сайта.";
            }
            elseif (!empty ($user))
            {
                $_SESSION['user'] = array(
                    'id' => $user['id'],
                    'user_name' => $user['user_name'],
                    'access_id' => $user['access_id'] 
                );
                $this->redirect('/home');
            }
            else
            {
                $error = "Проверьте правильность введенных логина и пароля.";
            }
            
        }
        
        $array = $this->front_menu();
        $menu_name = $array[0];
        $items_menu = $array[1];

        $title = 'Вход';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/login.php';
        require 'front/footer.php';
    }
    
    function signup()
    {
        if (isset($_POST) && !empty ($_POST))
        {
            $data = array(
                'user_name' => $_POST['login'],
                'email' => $_POST['email'],
                'pass' => md5($_POST['pass']),
                'register_date' => time(),
                'last_login' => time(),
                'access_id' => 2
            );
            
            $this->insert($data, 'users');
            $this->redirect('/profile/login');
        }
        
        $array = $this->front_menu();
        $menu_name = $array[0];
        $items_menu = $array[1];
        
        $title = 'Регистрация';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/signup.php';
        require 'front/footer.php';
    }

}

?>
