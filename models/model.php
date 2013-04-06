<?php

require_once 'database.php';

abstract class Model implements Database
{

    public $CLASS_NAME;

    public function load_collection($table)
    {
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        $query = "SELECT * FROM {$table} WHERE trash!=1";
        $result = $this->sql($query);
        return $result;
    }
    
    public function select($where, $table)
    {
        // var_dump(debug_backtrace());
        //$class = debug_backtrace();
        //$this->CLASS_NAME = $class[1]['class'];
        //eval('$table =' . $this->CLASS_NAME . '::table_name();');

        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($where))
        {
            $max_count = count($where) - 1;
            $count = 0;
            $aux_where = NULL;
            foreach ($where as $key => $value)
            {
                ($count == $max_count) ? $aux_where.= "{$key} = '{$value}'" : $aux_where.= "{$key} = '{$value}' AND ";
                $count++;
            }

            //$this->set_where($aux_where);
        }
        else
        {
            $aux_where = "id = '{$where}'";
            //$this->set_where($aux_where);
        }


        $query = "SELECT * FROM {$table} WHERE {$aux_where}";
        //var_dump($query);
        $result = Core::$_db->sql($query);
        return $result;
    }

    public function insert($data, $table)
    {
        //var_dump($data);die();
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($data))
        {
            $max_count = count($data) - 1;
            $count = 0;
            $key_data = NULL;
            $values_data = NULL;
            foreach ($data as $key => $value)
            {
                if ($max_count != $count)
                {
                    $key_data.= $key . ', ';
                    $values_data.= '\'' . $value . '\'' . ', ';
                }
                else
                {
                    $key_data.=$key;
                    $values_data.='\'' . $value . '\'' ;
                }
                $count++;
            }
            $query = "INSERT INTO {$table} ({$key_data}) VALUES ({$values_data})";
            //  var_dump($query);die();
            $result = $this->sql($query);
            $query = "SELECT MAX(LAST_INSERT_ID( id )) FROM {$table} LIMIT 1";
            $result = $this->sql($query);
            return $result["MAX(LAST_INSERT_ID( id ))"];
        }
        else
        {
            $message = <<<_EXC_MESSAGE
Check sending params. You must give only array to function insert(array).
_EXC_MESSAGE;
            throw new Exception($message);
        }
    }

    public function update($data, $table)
    {
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($data) && array_key_exists('id', $data))
        {
            $max_count = count($data) - 1;
            $count = 0;
            $aux_update = NULL;
            foreach ($data as $key => $value)
            {
                if ($key != 'id')
                {
                    ($count == $max_count) ? $aux_update.=$key . '=' . "'{$value}'" : $aux_update.=$key . '=' . "'{$value}', ";
                }
                else
                {
                    $id = $value;
                }
                $count++;
            }
            $query = "UPDATE {$table} SET {$aux_update} WHERE id='{$id}'";
            $result = $this->sql($query);
            return $result;
        }
        else
        {
            $message = <<<_EXC_MESSAGE
Check sending params. You must give only array to function update(array) and your request must contains ID field.
_EXC_MESSAGE;
            throw new Exception($message);
        }
    }

    public function delete($where, $table)
    {
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($where))
        {
            $max_count = count($where) - 1;
            $count = 0;
            $aux_del = NULL;
            foreach ($where as $key => $value)
            {
                ($count == $max_count) ? $aux_del.=$key . '=' . "'{$value}'" : $aux_del.=$key . '=' . "'{$value}' AND ";

                $count++;
            }

            $query = "DELETE FROM {$table} WHERE {$aux_del}";
            $result = $this->sql($query);
        }
        else
        {
            $message = <<<_EXC_MESSAGE
Check sending params. You must give only array to function delete(array).
_EXC_MESSAGE;
            throw new Exception($message);
        }
    }

}

?>
