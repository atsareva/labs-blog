<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_User_Status extends Model
{

    public function setTable()
    {
        $this->_tableName = 'user_status';
    }

}

?>
