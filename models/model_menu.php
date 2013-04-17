<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_Menu extends Model
{

    public function setTable()
    {
        $this->_tableName = 'menu';
    }

    /**
     * Retrieve main menu
     *
     * @return array
     */
    public function getMenu()
    {
        unset($_SESSION['user']);
        $this->addFieldToFilter('trash', array('=' => 0));
        $this->addFieldToFilter('status', array('=' => 1));
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
        return $this->getCollection()->getData();
    }

}

