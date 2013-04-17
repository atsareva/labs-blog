<?php

class View
{

    public $_template = array(
        'path' => "page/two-column",
        'data' => array());
    public $_content  = array('data' => array());
    public $_head     = array(
        'path' => 'page/html/head',
        'data' => array());
    public $_header   = array(
        'path' => 'page/html/header',
        'data' => array());
    public $_footer   = array(
        'path' => 'page/html/footer',
        'data' => array());
    public $_navBar   = array(
        'path' => 'page/html/navbar',
        'data' => array());
    public $_mainNav  = array(
        'path' => 'page/html/main-nav',
        'data' => array());

    public function __construct()
    {
        //set the full path to all property
        foreach ($this as $key => $property)
            if (isset($property['path']))
                $this->{$key}['path'] = VPATH . $property['path'] . EXT;
        $this->setTemplate();
    }

    public function __destruct()
    {
        echo $this->render($this->_template['path'], $this->_template['data']);
    }

    /**
     * Set main page template
     *
     * @param string $path
     * @param array $data
     * @return \View
     */
    public function setTemplate($path = NULL, $data = array())
    {
        ($path) ? $this->_template['path'] = VPATH . $path . EXT : $this->_template['path'] = VPATH . $this->_template['path'] . EXT;
        $this->_template['data'] = $data;
        return $this;
    }

    /**
     * Set child block for page
     *
     * @param string $type
     * @param string $path
     * @param array $data
     * @return \View
     */
    public function setChild($type, $path = NULL, $data = array())
    {
        ($path) ? $this->{"_$type"}['path'] = VPATH . $path . EXT : $this->{"_$type"}['path'] = VPATH . $this->{"_$type"}['path'] . EXT;
        $this->{"_$type"}['data'] = $data;
        return $this;
    }

    /**
     * Get child block for page by block type
     *
     * @param type $child
     * @return boolean|html
     */
    public function getChild($child = NULL)
    {
        if ($child && is_array($child) && isset($child['path']) && isset($child['data']))
            return $this->render($child['path'], $child['data']);
        return false;
    }

    /**
     * Render html
     *
     * @param string $template
     * @param array $data
     * @return html
     * @throws Exception
     */
    public function render($template, $data = array())
    {
        extract($data, EXTR_SKIP);

        // Capture the view output
        ob_start();

        try
        {
            // Load the view within the current scope
            include $template;
        }
        catch (Exception $e)
        {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
    }

}