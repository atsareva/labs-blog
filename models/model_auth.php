<?php
require CORE_PATH . 'model/model' . EXT;

class Model_Auth extends Model
{
    public function setTable()
    {
        $this->_tableName = 'users';
    }
}
?>
