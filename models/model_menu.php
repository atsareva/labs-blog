<?php
require CORE_PATH . 'model/model' . EXT;

class Model_Menu extends Model
{
    public function setTable()
    {
        $this->_tableName = 'items_menu';
    }
}

