<?php

require_once 'core.php';
require_once 'controller_auth.php';
require_once 'controller_content.php';
require_once 'controller_category.php';
require_once 'controller_menu.php';
require_once 'controller_users.php';
require_once 'controller_favourite.php';

class Controller_Admin extends Core
{

    public function __construct()
    {
        parent::__construct();
        if ((!isset($_SESSION['user']) || empty($_SESSION['user'])) || $_SESSION['user']['access_id'] < 3)
        {
            header('Location: http://' . $this->BASE_URL . '/auth/login');
        }
    }

    public function index()
    {
        $query = "SELECT * FROM users WHERE id = '{$_SESSION['user']['id']}'";
        $user = $this->sql($query);

        $title = "Панель управления";
        $menu_index = TRUE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/index.php';
        require 'footer.php';
    }

    public function profile()
    {
        $obj = new Controller_Auth();
        if (isset($_POST) && !empty($_POST))
        {
            $result = $obj->change_profile($_POST);
        }
        else
        {
            $result = $obj->change_profile();
            //var_dump($result);die();
        }
        unset($obj);

        $query = "SELECT * FROM faculties";
        $faculties = $this->sql($query);

        $query = "SELECT * FROM departments";
        $departments = $this->sql($query);

        $query = "SELECT * FROM user_status";
        $user_status = $this->sql($query);

        if (isset($_POST['save_exit']) && $_POST['save_exit'] == 1)
        {
            header('Location: http://' . $this->BASE_URL . '/admin');
        }

        $title = "Мой профиль";
        $menu_profile = TRUE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/profile.php';
        require 'footer.php';
    }

    public function content()
    {
        $obj = new Controller_Content();
        $data = $obj->load_material();
        unset($obj);

        $title = "Менеджер материалов";
        $menu_content = TRUE;
        $_SESSION['favourite'] = FALSE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/material.php';
        require 'footer.php';
    }

    public function favourite()
    {
        $obj = new Controller_Favourite();
        $data = $obj->load_material();
        unset($obj);

        $title = "Менеджер материалов";
        $menu_favourite = TRUE;
        $_SESSION['favourite'] = TRUE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/material.php';
        require 'footer.php';
    }

    public function category()
    {
        $obj = new Controller_Category();
        $data = $obj->load_category();
        unset($obj);

        $title = "Менеджер категорий";
        $menu_category = TRUE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/category.php';
        require 'footer.php';
    }

    public function menu()
    {
        $obj = new Controller_Menu();



        $title = "Менеджер меню";
        $menu_menu = TRUE;

        $query = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';

        if (isset($_GET['id']) && $_GET['id'] > 0)
        {
            $data = $obj->load_items($_GET['id']);
            require 'admin/menu_item.php';
        }
        else
        {
            $data = $obj->load_menu();
            $count = $obj->count_type_items($data);
            require 'admin/content_menu.php';
        }
        unset($obj);

        require 'footer.php';
    }

    function users()
    {
        $obj = new Controller_Users();
        $array = $obj->load_users();

        $data = $array[0];
        $admin = $array[1];

        unset($obj);

        $title = "Пользователи";
        $menu_users = TRUE;

        $query = "SELECT * FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/users.php';
        require 'footer.php';
    }

}

?>