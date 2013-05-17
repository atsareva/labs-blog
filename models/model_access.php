<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_Access extends Model
{

    public function setTable()
    {
        $this->_tableName = 'access';
    }

    public function getAccess()
    {
        return $this->addFieldToFilter('title', array('!=' => 'for_superadmin'))
                        ->getCollection()
                        ->getData();
    }

}

?>
