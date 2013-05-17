<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Menu extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        /**
         * @todo Validation
         */
        if (isset($_POST) && !empty($_POST))
        {
            $data = $_POST;
            unset($data['save_exit']);

            $menu = Core::getModel('menu')->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/menu');
            else
                $this->redirect('menu/edit/' . $menu->getId());
        }

        $head    = 'Создать меню';
        $menuUrl = Core::getBaseUrl() . 'menu/create';

        $access = Core::getModel('access')->getAccess();

        $this->_view->setTitle('Создать меню')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                ->setChild('content', 'admin/menu/form_menu', array('head'    => $head, 'menuUrl' => $menuUrl, 'access'  => $access));
    }

    public function edit($id)
    {
        /**
         * @todo Validation
         */
        if (isset($id[0]))
        {
            $menu = Core::getModel('menu')->load((int) $id[0]);
            if ($menu->getId())
            {
                if (isset($_POST) && !empty($_POST))
                {
                    $data = $_POST;
                    unset($data['save_exit']);

                    $menu->setData($data)->save();

                    $saveExit = $_POST['save_exit'];
                    unset($_POST);

                    if ($saveExit)
                        $this->redirect('admin/menu');
                    else
                        $this->redirect('menu/edit/' . $menu->getId());
                }

                $head    = 'Редактировать меню';
                $menuUrl = Core::getBaseUrl() . 'menu/edit/' . $menu->getId();

                $access = Core::getModel('access')->getAccess();

                $this->_view->setTitle('Редактировать меню')
                        ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                        ->setChild('content', 'admin/menu/form_menu', array('head'    => $head, 'menu'    => $menu, 'menuUrl' => $menuUrl, 'access'  => $access));
            }
            else
                $this->redirect('admin/menu');
        }
        else
            $this->redirect('admin/menu');
    }

    public function remove()
    {
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('menu')->load((int) $id)->unsetData()->save();
    }

    public function publicMenu()
    {
        $ids  = explode(',', trim($_POST['ids'], ','));
        $bool = $_POST['bool'];

        foreach ($ids as $id)
            Core::getModel('menu')->load((int) $id)->setData('status', $bool)->save();
    }

    public function createItem($menuId)
    {
        if (isset($menuId[0]))
        {
            $modelMenuItems = Core::getModel('menu_items');

            if (isset($_POST) && !empty($_POST))
            {

                /**
                 * @todo Validation
                 */
                $data            = $_POST;
                $data['menu_id'] = (int) $menuId[0];
                unset($data['save_exit']);

                $menuItem = $this->saveItem($modelMenuItems, $data);

                $saveExit = $_POST['save_exit'];
                unset($_POST);

                if ($saveExit)
                    $this->redirect('admin/menu/' . $menuId[0]);
                else
                    $this->redirect('menu/editItem/' . $menuId[0] . '/' . $menuItem->getId());
            }

            $head    = 'Создать пункт меню';
            $menuUrl = Core::getBaseUrl() . 'menu/createItem/' . $menuId[0];

            $menuItems = $modelMenuItems->cleanQuery()->loadMenuItems((int) $menuId[0], NULL);
            $access    = Core::getModel('access')->getAccess();

            $this->_view->setTitle('Создать пункт меню')
                    ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                    ->setChild('content', 'admin/menu/form_menu_item', array('head'      => $head, 'menuUrl'   => $menuUrl, 'menuItems' => $menuItems, 'access'    => $access, 'menuId'    => (int) $menuId[0]));
        }
        else
            $this->redirect('admin/menu');
    }

    public function editItem($param)
    {
        if (isset($param[0]) && isset($param[1]))
        {
            $modelMenuItems = Core::getModel('menu_items');

            $menuItem = clone $modelMenuItems->load($param[1]);

            if (isset($_POST) && !empty($_POST))
            {

                /**
                 * @todo Validation
                 */
                $data            = $_POST;
                $data['menu_id'] = (int) $param[0];
                unset($data['save_exit']);

                $menuItem = $this->saveItem($modelMenuItems, $data, $menuItem);

                $saveExit = $_POST['save_exit'];
                unset($_POST);

                if ($saveExit)
                    $this->redirect('admin/menu/' . $param[0]);
                else
                    $this->redirect('menu/editItem/' . $param[0] . '/' . $menuItem->getId());
            }

            $head    = 'Редактировать пункт меню';
            $menuUrl = Core::getBaseUrl() . 'menu/editItem/' . $param[0] . '/' . $param[1];

            $menuItems = $modelMenuItems->cleanQuery()->loadMenuItems((int) $param[0], NULL);
            $access    = Core::getModel('access')->getAccess();

            $orderOf = $modelMenuItems->cleanQuery()
                    ->addFieldToFilter('parent_id', array('=' => $menuItem->getParentId()))
                    ->addFieldToFilter('menu_id', array('=' => $menuItem->getMenuId()))
                    ->getCollection()
                    ->getData();

            $this->_view->setTitle('Редактировать пункт меню')
                    ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                    ->setChild('content', 'admin/menu/form_menu_item', array(
                        'head'      => $head,
                        'menuUrl'   => $menuUrl,
                        'menuItem'  => $menuItem,
                        'menuItems' => $menuItems,
                        'access'    => $access,
                        'menuId'    => (int) $param[0],
                        'orderOf'   => $orderOf
            ));
        }
        else
            $this->redirect('admin/menu');
    }

    public function saveItem($modelMenuItems, $data, $menuItem = NULL)
    {
        /**
         * set dash for new menu item
         */
        $parent       = $modelMenuItems->load((int) $data['parent_id']);
        if ($parent->getId())
            $data['dash'] = ($parent->getDash() + 1);
        unset($parent);

        /**
         * if index == 1 reset old index and set new
         */
        if ($data['for_index'] == 1)
        {
            $collection = $modelMenuItems->cleanQuery()
                    ->addFieldToFilter('for_index', 1)
                    ->getCollection()
                    ->getData();
            foreach ($collection as $collectionItem)
                if (isset($collectionItem->for_index))
                    $modelMenuItems->cleanQuery()->load($collectionItem->id)->setData('for_index', 0)->save();

            unset($collection);
        }

        if ($menuItem && $menuItem->getId() != $data['order_of'])
        {
            /**
             * change order_of
             */
            $changedOrderOf = $modelMenuItems->load((int) $data['order_of']);
            if ($changedOrderOf->getId())
            {
                $data['order_of'] = $changedOrderOf->getOrderOf();
                $changedOrderOf->setOrderOf($menuItem->getOrderOf())->save();
            }
        }
        elseif ($menuItem)
        {
            $data['order_of'] = $menuItem->getOrderOf();
        }
        else
        {
            /**
             * get max order_of for sibling items
             */
            $maxOrder = $modelMenuItems->cleanQuery()
                    ->addFieldToFilter('parent_id', array('=' => $data['parent_id']))
                    ->addFieldToFilter('MAX(order_of) AS order_of')
                    ->getCollection()
                    ->getData();

            if (isset($maxOrder[0]))
                $data['order_of'] = $maxOrder[0]->order_of + 1;
        }

        /**
         * save menu item+
         */
        if ($menuItem)
            $menuItem->setData($data)->save();
        else
            $menuItem = $modelMenuItems->cleanQuery()->setData($data)->save();
        return $menuItem;
    }

    public function publicMenuItem()
    {
        $ids  = explode(',', trim($_POST['ids'], ','));
        $bool = $_POST['bool'];

        foreach ($ids as $id)
            Core::getModel('menu_items')->load((int) $id)->setData('status', $bool)->save();
    }

    public function removeItem()
    {
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('menu_items')->load((int) $id)->unsetData()->save();
    }

}

?>
