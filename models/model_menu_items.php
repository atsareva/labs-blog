<?php

require CORE_PATH . 'model/model' . EXT;

class Model_Menu_Items extends Model
{

    public $_menuName  = array();
    public $_menuItems = array();

    public function setTable()
    {
        $this->_tableName = 'items_menu';
    }

    public function getMainNav()
    {
        $mainNav = Core::getModel('menu')->getMenu();
        if (count($mainNav) > 0)
        {
            foreach ($mainNav as $item)
            {
                $itemId = $item>id;
                $this->_menuItems[$itemId] = $this->_loadMenuItems($itemId);
                if ($item['show_title'] == 1)
                    $this->_menuName[$itemId]  = $item->title();
            }
        }
        return $this;
    }

    private function _loadMenuItems($id = NULL)
    {
        if ($id)
            $this->addFieldToFilter('menu_id', array('=' => (int) $id));
        
        $result = $this->getCollection()->getData();

        if (count($result) == 0)
            return FALSE;
        elseif (count($result) == 1)
        {

            /**
             *  very very GAVNOKOD, but it works
             */
            $data      = $result;
            $result    = NULL;
            $result[0] = $data;
        }

        $result = self::menu_tree($result);
        return $result;
    }

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

