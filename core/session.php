<?php

class Session
{

    /**
     * Items data
     *
     * @var array
     */
    public $_session = array();

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
        else
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

}