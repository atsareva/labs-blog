<?php
require_once CORE_PATH . 'model/model' . EXT;

class Model_User extends Model
{
    public function setTable()
    {
        $this->_tableName = 'users';
    }
}
?>
