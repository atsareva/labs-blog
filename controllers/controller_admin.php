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

    function users()
    {
        $user = Core::getHelper('user')->getUserInfo();

        $users = Core::getModel('user')->loadUsers($user->getAccessId());

        $this->_view->setTitle('Пользователи')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuUsers' => true))
                ->setChild('content', 'admin/user/users', array('users' => $users));
    }

    public function menu($id)
    {
        $user = Core::getHelper('user')->getUserInfo();

        if (isset($id[0]))
        {
            $menuItems = Core::getModel('menu_items')->cleanQuery()->loadMenuItems((int) $id[0]);
            $this->_view->setChild('content', 'admin/menu/menu_item', array('menuItems' => $menuItems));
        }
        else
        {
            $menu = Core::getModel('menu_items')->countTypeItems(Core::getModel('menu')->loadMenu($user->getAccessId()));
            $this->_view->setChild('content', 'admin/menu/content_menu', array('menu' => $menu));
        }
        $this->_view->setTitle('Менеджер меню')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true));
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

}

?>
