<?php
require CORE_PATH . 'model/model' . EXT;

class Model_Auth extends Model
{
    public function __construct()
    {
        $this->_tableName = 'users';
    }
}
?>
