<?php

require_once 'db/db' . EXT;

abstract class Model// extends Db
{

    /**
     * Items data
     *
     * @var array
     */
    public $_data = array();

    /**
     * Last insert id to database
     * 
     * @var int 
     */
    public $_lastInsertId;

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
     * String that will be added to query
     *
     * @var string
     */
    private $_join = '';

    /**
     * Order By string that will be added to query
     * 
     * @var string
     */
    private $_orderBy = '';

    /**
     * Set data object id if its will deleted
     * 
     * @var boolean | int
     */
    private $_unsetDataFlag = FALSE;
    private $_db;

    public function __construct()
    {
        $this->_db = Db::getInstance();
        //set table
        $this->setTable();
    }

    /**
     * Retreive select query result
     *
     * @param int $id
     * @return object
     */
    public function load($id)
    {
        if ($id)
        {
            (empty($this->_filterQuery)) ? $filterQuery = '' : $filterQuery = 'AND ' . $this->_filterQuery;
            ($this->_orderBy) ? $orderBy     = ' ORDER BY ' . $this->_orderBy : $orderBy     = '';
            (empty($this->_join)) ? $join        = '' : $join        = $this->_join;

            $query       = "SELECT * FROM {$this->_tableName} {$join} WHERE {$this->_tableName}.id = {$id}{$orderBy} {$filterQuery}";
            $this->_data = (object) $this->_db->sql($query);
        }
        return $this;
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
        (empty($this->_orderBy)) ? $orderBy     = '' : $orderBy     = ' ORDER BY ' . $this->_orderBy;
        (empty($this->_join)) ? $join        = '' : $join        = $this->_join;

        $query = "SELECT {$filterField} FROM {$this->_tableName} {$join} {$filterQuery}{$orderBy}";

        $result        = $this->_db->sql($query);
        if (isset($result[0]) && is_array($result[0]))
            foreach ($result as $value)
                $this->_data[] = (object) $value;
        else
            $this->_data[] = (object) $result;
        return $this;
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
        {
            if (is_array($field))
                foreach ($field as $value)
                    (!empty($this->_filterField)) ? $this->_filterField = $this->_filterField . ', ' . $value : $this->_filterField = $value;
            else
                (!empty($this->_filterField)) ? $this->_filterField = $this->_filterField . ', ' . $field : $this->_filterField = $field;
        }
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
                (empty($this->_filterQuery)) ? $this->_filterQuery .= ' (' : $this->_filterQuery .= ' AND (';

                foreach ($options as $value)
                {
                    ($count == $maxCount) ? $this->_filterQuery .= $this->_buildQuery($field, $value) . ' )' : $this->_filterQuery .= $this->_buildQuery($field, $value) . ' OR ';
                    $count++;
                }
            }
        }
        return $this;
    }

    public function join($table, $cond, $field, $key = 'inner')
    {
        $this->addFieldToFilter($field);
        $this->_join .= " " . strtoupper($key) . " JOIN {$table} ON {$cond}";
        return $this;
    }

    public function orderBy($field)
    {
        if (is_array($field))
        {
            $count    = 0;
            $countMax = (count($field) - 1);
            foreach ($field as $key => $value)
            {
                ($count == $countMax) ? $this->_orderBy .= $key . ' ' . $value : $this->_orderBy .= $key . ' ' . $value . ', ';
                $count++;
            }
        }
        else
            $this->_orderBy .= $field;
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

    /**
     * Overwrite data in the object.
     *
     * @param string|array $key
     * @param mixed $value
     * @return object
     */
    public function setData($key, $value = null)
    {
        if (is_array($key))
        {
            if (isset($this->_data->id))
                $key['id'] = $this->_data->id;

            $this->_data       = (object) $key;
        }
        else
            $this->_data->$key = $value;

        return $this;
    }

    /**
     * Unset data from the object.
     *
     * @param string|array $key
     * @return object
     */
    public function unsetData($key = NULL)
    {
        if (is_null($key))
        {
            $this->_unsetDataFlag = $this->getId();
            $this->_data          = array();
        }
        else
            unset($this->_data->$key);

        return $this;
    }

    /**
     * Save data object
     *
     * @return object
     */
    public function save()
    {
        if (!$this->getData() && !$this->_unsetDataFlag)
        {
            return $this;
        }
        // delete record by id
        elseif ($this->_unsetDataFlag)
        {
            $this->_delete($this->_unsetDataFlag);
            return $this;
        }
        // update record by id
        elseif ($this->getId())
        {
            //get id from data
            $id = $this->getId();
            $this->unsId();

            //update data
            $this->_update($id, (array) $this->getData());
        }
        // insert new record
        else
        {
            $id                  = $this->_insert((array) $this->getData());
            $this->_lastInsertId = $id;
        }
        return $this->load($id);
    }

    private function _insert($data)
    {
        $maxCount   = count($data) - 1;
        $count      = 0;
        $keyData    = NULL;
        $valuesData = NULL;
        foreach ($data as $key => $value)
        {
            if ($maxCount != $count)
            {
                $keyData .= $key . ', ';
                $valuesData .= "'{$value}', ";
            }
            else
            {
                $keyData .= $key;
                $valuesData .= "'{$value}'";
            }
            $count++;
        }
        $this->_db->sql("INSERT INTO {$this->_tableName} ({$keyData}) VALUES ({$valuesData})");
        $result = $this->_db->sql("SELECT MAX(LAST_INSERT_ID( id )) FROM {$this->_tableName} LIMIT 1");
        return $result["MAX(LAST_INSERT_ID( id ))"];
    }

    /**
     * Update data in database by id
     *
     * @param int $id
     * @param array $data
     * @return type
     */
    private function _update($id, $data)
    {
        $count      = 0;
        $maxCount   = count($data) - 1;
        $queryBuild = NULL;
        foreach ($data as $key => $value)
        {
            ($count == $maxCount) ? $queryBuild .= $key . '=' . "'{$value}'" : $queryBuild .= $key . '=' . "'{$value}', ";
            $count++;
        }
        $query = "UPDATE {$this->_tableName} SET {$queryBuild} WHERE id='{$id}'";
        return $this->_db->sql($query);
    }

    /**
     * Execute query for deliting record by id
     *
     * @param int $id
     */
    private function _delete($id)
    {
        $this->_db->sql("DELETE FROM {$this->_tableName} WHERE id = {$id}");
    }

    /**
     * Retrieves data from the object
     *
     * @param string $key
     * @param string $index
     * @return null
     */
    public function getData($key = '', $index = NULL)
    {
        if ($key === '')
            return $this->_data;
        if (isset($this->_data->$key))
        {
            if (is_null($index))
                return $this->_data->$key;
            $value = $this->_data->$key;
            if (is_array($value))
            {
                if (isset($value[$index]))
                    return $value[$index];
                return NULL;
            }
        }
        return NULL;
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
                $key  = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", substr($method, 3)));
                $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                return $data;

            case 'set' :
                $key    = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", substr($method, 3)));
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                return $result;

            case 'uns' :
                $key    = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", substr($method, 3)));
                $result = $this->unsetData($key);
                return $result;

            case 'has' :
                $key = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", substr($method, 3)));
                return isset($this->_data->$key);
        }
        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }

    public function getTable()
    {
        
    }

}

?>
