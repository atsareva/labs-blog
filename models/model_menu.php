<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_Menu extends Model
{

    public function setTable()
    {
        $this->_tableName = 'menu';
    }

    /**
     * Retrieve main menu for frontend
     *
     * @return array
     */
    public function getMenu()
    {
        $user     = Core::getHelper('user')->getUserInfo();
        $accessId = 1;

        if ($user && $user->getAccessId())
            $accessId = $user->getAccessId();


        return $this->addFieldToFilter('trash', array('=' => 0))
                        ->addFieldToFilter('status', array('=' => 1))
                        ->addFieldToFilter('access_id', array('<=' => $accessId))
                        ->getCollection()->getData();
    }

    /**
     * Retrieve menu for backend
     * 
     * @return array
     */
    public function loadMenu($accessId)
    {
        return $this->addFieldToFilter('menu.*')
                        ->addFieldToFilter('access_id', array('<=' => $accessId))
                        ->getCollection()->getData();
    }

}

