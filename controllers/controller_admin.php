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
            $menuItems = Core::getModel('menu_items')->cleanQuery()->loadMenuItems((int) $id[0], NULL);
            $this->_view->setChild('content', 'admin/menu/menu_item', array('menuItems' => $menuItems, 'menuId'    => (int) $id[0]));
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
        $user = Core::getHelper('user')->getUserInfo();

        $head      = 'Менеджер материалов';
        $materials = Core::getModel('material')
                ->addFieldToFilter('materials.*')
                ->addFieldToFilter('materials.trash', array('=' => 0))
                ->join('categories', 'categories.id = materials.category_id', 'categories.title AS category_title', 'left')
                ->join('users', 'users.id = materials.author_id', 'users.full_name AS author')
                ->getCollection()
                ->getData();

        $this->_view->setTitle('Менеджер материалов')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('$menuContent' => true))
                ->setChild('content', 'admin/material/material', array('materials' => $materials, 'head'      => $head));
    }

    public function favourite()
    {
        $user = Core::getHelper('user')->getUserInfo();

        $head      = 'Избранные материалы';
        $materials = Core::getModel('material')
                ->addFieldToFilter('materials.*')
                ->addFieldToFilter('materials.trash', array('=' => 0))
                ->addFieldToFilter('materials.favorite', array('=' => 1))
                ->join('categories', 'categories.id = materials.category_id', 'categories.title AS category_title', 'left')
                ->join('users', 'users.id = materials.author_id', 'users.full_name AS author')
                ->getCollection()
                ->getData();

        $this->_view->setTitle('Менеджер материалов')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('$menuContent' => true))
                ->setChild('content', 'admin/material/material', array('materials' => $materials, 'head'      => $head));
    }

    public function category()
    {

        $user = Core::getHelper('user')->getUserInfo();

        $head      = 'Менеджер категорий';
        $categories = Core::getModel('category')
                ->addFieldToFilter('categories.*')
                ->addFieldToFilter('categories.trash', array('=' => 0))
                ->join('users', 'users.id = categories.author_id', 'users.full_name AS author')
                ->getCollection()
                ->getData();

        $this->_view->setTitle($head)
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('$menuCategory' => true))
                ->setChild('content', 'admin/category/category', array('categories' => $categories, 'head'       => $head));
    }

}

?>
