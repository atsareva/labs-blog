<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Admin extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_view->setTitle('Панель управления')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuIndex' => true))
                ->setChild('content', 'admin/index')
                ->setBaseClass('login');
    }

    public function content()
    {
        $obj  = new Controller_Content();
        $data = $obj->load_material();
        unset($obj);

        $title                 = "Менеджер материалов";
        $menu_content          = TRUE;
        $_SESSION['favourite'] = FALSE;

        $query      = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/material.php';
        require 'footer.php';
    }

    public function favourite()
    {
        $obj  = new Controller_Favourite();
        $data = $obj->load_material();
        unset($obj);

        $title                 = "Менеджер материалов";
        $menu_favourite        = TRUE;
        $_SESSION['favourite'] = TRUE;

        $query      = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/material.php';
        require 'footer.php';
    }

    public function category()
    {
        $obj  = new Controller_Category();
        $data = $obj->load_category();
        unset($obj);

        $title         = "Менеджер категорий";
        $menu_category = TRUE;

        $query      = "SELECT id, title FROM menu WHERE trash!=1";
        $admin_menu = $this->sql($query);

        require 'head.php';
        require 'admin/menu.php';
        require 'admin/category.php';
        require 'footer.php';
    }

    public function menu()
    {
        $obj = new Controller_Menu();



        $title     = "Менеджер меню";
        $menu_menu = TRUE;

        $query      = "SELECT id, title FROM menu WHERE trash!=1";
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
            $data  = $obj->load_menu();
            $count = $obj->count_type_items($data);
            require 'admin/content_menu.php';
        }
        unset($obj);

        require 'footer.php';
    }

    function users()
    {
        $user = Core::getHelper('user')->getUserInfo();

        if ($user->getAccessId() == 5)
            $users = Core::getModel('user')
                            ->addFieldToFilter('users.*')
                            ->join('user_status', 'user_status.id = users.status_id', 'user_status.name AS status_id')
                            ->join('access', 'access.id = users.access_id', 'access.description AS access')
                            ->getCollection()->getData();
        else
            $users = Core::getModel('user')
                            ->addFieldToFilter('users.*')
                            ->addFieldToFilter('access_id', array('<' => 4))
                            ->join('user_status', 'user_status.id = users.status_id', 'user_status.name AS status_id')
                            ->join('access', 'access.id = users.access_id', 'access.description AS access')
                            ->getCollection()->getData();

        $this->_view->setTitle('Пользователи')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuUsers' => true))
                ->setChild('content', 'admin/users', array('users' => $users));
    }

}

?>
