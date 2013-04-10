<?php

require_once 'database' . EXT;
require_once 'db/db' . EXT;

abstract class Model extends Db implements Database
{

    /**
     * Items data
     *
     * @var array
     */
    public $_data        = array();

    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName;

    /**
     * Fields that will be added to the filtering
     *
     * @var string
     */
    private $_filterField = '';

    /**
     * String that will be added to query
     *
     * @var string
     */
    private $_filterQuery = '';

    /**
     * Retreive select query result
     *
     * @param int $id
     * @return object
     */
    public function load($id)
    {
        $query       = "SELECT * FROM {$this->_tableName} WHERE id = {$id}";
        $this->_data = (object) $this->sql($query);
    }

    /**
     * Retreive all data
     *
     * @return object
     */
    public function getCollection()
    {
        (empty($this->_filterField)) ? $filterField = '*' : $filterField = $this->_filterField;
        (empty($this->_filterQuery)) ? $filterQuery = '' : $filterQuery = 'WHERE ' . $this->_filterQuery;
        $query       = "SELECT {$filterField} FROM {$this->_tableName} {$filterQuery}";

        $result        = $this->sql($query);
        if (isset($result[0]) && is_array($result[0]))
            foreach ($result as $value)
                $this->_data[] = (object) $value;
        else
            $this->_data[] = (object) $result;
    }

    /**
     * Add field to filter
     *
     * @param string $field
     * @param array $options
     */
    public function addFieldToFilter($field, $options = array())
    {
        if (count($options) == 0)
            (!empty($this->_filterField)) ? $this->_filterField = $this->_filterField . ', ' . $field : $this->_filterField = $field;
        else
        {
            // build terms for AND
            if (count($options) == 1)
                (empty($this->_filterQuery)) ? $this->_filterQuery .= $this->_buildQuery($field, $options) : $this->_filterQuery .= ' AND ' . $this->_buildQuery($field, $options);
            else
            {
                // build terms for OR
                $count    = 0;
                $maxCount = count($options) - 1;
                $this->_filterQuery .= ' (';

                foreach ($options as $value)
                {
                    ($count == $maxCount) ? $this->_filterQuery .= $this->_buildQuery($field, $value) . ' )' : $this->_filterQuery .= $this->_buildQuery($field, $value) . ' OR ';
                    $count++;
                }
            }
        }
        return $this;
    }

    /**
     * Build query for filter
     *
     * @param string $field
     * @param array $optionArray
     * @return string
     */
    private function _buildQuery($field, $optionArray)
    {
        $buildQuery = NULL;
        foreach ($optionArray as $key => $value)
            $buildQuery.= "( {$field} {$key} '{$value}' )";

        return $buildQuery;
    }

    public function setData($data)
    {

    }

    public function save()
    {
        
    }

    public function insert($data)
    {
        if (is_array($data))
        {
            $max_count   = count($data) - 1;
            $count       = 0;
            $key_data    = NULL;
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
                    $values_data.='\'' . $value . '\'';
                }
                $count++;
            }
            $query  = "INSERT INTO {$table} ({$key_data}) VALUES ({$values_data})";
            //  var_dump($query);die();
            $result = $this->sql($query);
            $query  = "SELECT MAX(LAST_INSERT_ID( id )) FROM {$table} LIMIT 1";
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

    public function update($data)
    {
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($data) && array_key_exists('id', $data))
        {
            $max_count  = count($data) - 1;
            $count      = 0;
            $aux_update = NULL;
            foreach ($data as $key => $value)
            {
                if ($key != 'id')
                {
                    ($count == $max_count) ? $aux_update.=$key . '=' . "'{$value}'" : $aux_update.=$key . '=' . "'{$value}', ";
                }
                else
                {
                    $id     = $value;
                }
                $count++;
            }
            $query  = "UPDATE {$table} SET {$aux_update} WHERE id='{$id}'";
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

    public function delete($where)
    {
        //$class_name = get_called_class();
        //$table = $class_name::table_name();

        if (is_array($where))
        {
            $max_count = count($where) - 1;
            $count     = 0;
            $aux_del   = NULL;
            foreach ($where as $key => $value)
            {
                ($count == $max_count) ? $aux_del.=$key . '=' . "'{$value}'" : $aux_del.=$key . '=' . "'{$value}' AND ";

                $count++;
            }

            $query  = "DELETE FROM {$table} WHERE {$aux_del}";
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

     /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3))
        {
            case 'get' :
                //Varien_Profiler::start('GETTER: '.get_class($this).'::'.$method);
                $key  = $this > _underscore(substr($method, 3));
                $data = $this > getData($key, isset($args[0]) ? $args[0] : null);
                //Varien_Profiler::stop('GETTER: '.get_class($this).'::'.$method);
                return $data;

            case 'set' :
                //Varien_Profiler::start('SETTER: '.get_class($this).'::'.$method);
                $key    = $this > _underscore(substr($method, 3));
                $result = $this > setData($key, isset($args[0]) ? $args[0] : null);
                //Varien_Profiler::stop('SETTER: '.get_class($this).'::'.$method);
                return $result;

            case 'uns' :
                //Varien_Profiler::start('UNS: '.get_class($this).'::'.$method);
                $key    = $this > _underscore(substr($method, 3));
                $result = $this > unsetData($key);
                //Varien_Profiler::stop('UNS: '.get_class($this).'::'.$method);
                return $result;

            case 'has' :
                //Varien_Profiler::start('HAS: '.get_class($this).'::'.$method);
                $key = $this > _underscore(substr($method, 3));
                //Varien_Profiler::stop('HAS: '.get_class($this).'::'.$method);
                return isset($this->_data[$key]);
        }
        throw new Varien_Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }


}

?>
