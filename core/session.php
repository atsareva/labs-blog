<?php

class Session
{

    /**
     * Items data
     *
     * @var array
     */
    public $_session = array(1);

    public function __construct()
    {
        $this->setData($_SESSION);
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
            $this->_session = (object) $key;
            $_SESSION       = $key;
        }
        elseif($value)
        {
            $this->_session->$key = $value;
            $_SESSION[$key]       = $value;
        }

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
            $this->_session = array();
            unset($_SESSION);
        }
        else
        {
            unset($this->_session->$key);
            unset($_SESSION[$key]);
        }

        return $this;
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
            return $this->_session;
        if (isset($this->_session->$key))
        {
            if (is_null($index))
                return $this->_session->$key;
            $value = $this->_session->$key;
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
                return isset($this->_session->$key);
        }
        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }

}