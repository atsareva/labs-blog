<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_Menu_Items extends Model
{

    /**
     * Menus name
     *
     * @var array
     */
    public $_menuName = array();

    /**
     * All menu items
     *
     * @var array
     */
    public $_menuItems = array();

    public function __construct()
    {
        parent::__construct();
        $this->getMenus();
    }

    public function setTable()
    {
        $this->_tableName = 'items_menu';
    }

    /**
     * Retrieve array with all menu items
     *
     * @return array
     */
    public function getMainNavItems()
    {
        foreach ($this->_menuName as $key => $value)
            $this->_menuItems[$key] = $this->_loadMenuItems($key);
        return $this->_menuItems;
    }

    /**
     * Retrieve all menus
     *
     * @return array
     */
    public function getMenus()
    {
        $mainNav = Core::getModel('menu')->getMenu();
        if (count($mainNav) > 0)
        {
            foreach ($mainNav as $item)
                if ($item->show_title == 1)
                    $this->_menuName[$item->id] = $item->title;
                else
                    $this->_menuName[$item->id] = FALSE;
        }
        return $this->_menuName;
    }

    /**
     * Load menu items by id
     *
     * @param int $id
     * @return boolean|array
     */
    private function _loadMenuItems($id = NULL)
    {
        if ($id)
            $this->addFieldToFilter('menu_id', array('=' => (int) $id));

        $this->_setAccessFilter();
        $result = $this->addFieldToFilter('status', array('=' => 1))
                        ->addFieldToFilter('trash', array('=' => 0))
                        ->orderBy('order_of')
                        ->getCollection()->getData();
        $this->cleanQuery();
        if (count($result) == 0)
            return FALSE;

        $menuTree = $this->_menuTree($result);
        return $menuTree;
    }

    /**
     * Recursive build array with menu items
     *
     * @param array $menuItems
     * @param array $menuTree
     * @param int $parentId
     * @return array
     */
    private function _menuTree($menuItems, $menuTree = array(), $parentId = 0)
    {
        foreach ($menuItems as $item)
        {
            if (isset($item->parent_id) && $parentId == $item->parent_id)
            {
                $access = Core::getModel('access')->load($item->access_id);

                $menuTree[$item->id] = array(
                    'title'  => $item->title,
                    'path'   => $item->path,
                    'status' => $item->status,
                    'access' => $access->getDescription(),
                    'dash'   => $item->dash
                );
                $menuTree            = $this->_menuTree($menuItems, $menuTree, $item->id);
            }
        }
        return $menuTree;
    }

    /**
     * Set filter for menu items
     */
    private function _setAccessFilter()
    {
        $user     = Core::getHelper('user')->getUserInfo();
        $accessId = 1;

        if ($user && $user->getAccessId())
            $accessId = $user->getAccessId();

        $this->addFieldToFilter('access_id', array('<=' => $accessId));
    }

    public function countTypeItems($menus)
    {
        foreach ($menus as $menu)
        {
            if (isset($menu->id))
            {
                $menu->public = 0;
                $menu->material_trash  = 0;

                $this->_setAccessFilter();
                $items = $this->addFieldToFilter('menu_id', array('=' => $menu->id))
                        ->getCollection()
                        ->getData();
                foreach ($items as $item)
                {
                    if (isset($item->id))
                    {
                        if ($item->status == 1)
                            $menu->public++;
                        if ($item->trash == 1)
                            $menu->material_trash++;
                    }
                }
                $menu->not_public = (count($items) - $menu->public);
                $this->cleanQuery();
            }
        }
        return $menus;
    }

}

