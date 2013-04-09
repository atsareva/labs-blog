<?php
require CORE_PATH . 'model/model' . EXT;

class Model_Auth extends Model
{
    public static function table_name()
    {
        return 'users';
    }
}
?>
