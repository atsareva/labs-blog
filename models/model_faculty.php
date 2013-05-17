<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_Faculty extends Model
{

    public function setTable()
    {
        $this->_tableName = 'faculties';
    }

}

?>
