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

        $this->addFieldToFilter('status', array('=' => 1));
        $this->addFieldToFilter('trash', array('=' => 0));

        $this->orderBy('order_of');
        $result = $this->getCollection()->getData();
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
            if ($parentId == $item->parent_id)
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
        $this->addFieldToFilter('access_id', array('=' => 1));
        $session = Core::getSession();
        if ($session->hasUser())
        {
            $accessId = $session->getData('user', 'access_id');
            switch ($accessId)
            {
                case 2:
                    $this->addFieldToFilter('access_id', array(
                        array('=' => 1),
                        array('=' => 2)
                    ));
                    break;
                case 3:
                    $this->addFieldToFilter('access_id', array(
                        array('=' => 1),
                        array('=' => 2),
                        array('=' => 3)
                    ));
                    break;
                case 4:
                    $this->addFieldToFilter('access_id', array(
                        array('=' => 1),
                        array('=' => 2),
                        array('=' => 3),
                        array('=' => 4)
                    ));
                    break;

                default:
                    break;
            }
        }
    }

}

