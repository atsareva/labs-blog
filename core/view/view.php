<?php

class View
{

    /**
     * Main page template
     *
     * @var array
     */
    public $_template = array(
        'path' => "front/page/two-column",
        'data' => array());

    /**
     * Page content view
     *
     * @var array
     */
    public $_content = array('data' => array());

    /**
     * Page head block
     *
     * @var array
     */
    public $_head = array(
        'path' => 'front/page/html/head',
        'data' => array());

    /**
     * Page header block
     *
     * @var array
     */
    public $_header = array(
        'path' => 'front/page/html/header',
        'data' => array());

    /**
     * Page footer block
     *
     * @var array
     */
    public $_footer = array(
        'path' => 'front/page/html/footer',
        'data' => array());

    /**
     * Page navbar block
     *
     * @var array
     */
    public $_navBar = array(
        'path' => 'front/page/html/navbar',
        'data' => array());

    /**
     * Page nabigation block
     *
     * @var array
     */
    public $_mainNav = array(
        'path' => 'front/page/html/main-nav',
        'data' => array());

    /**
     * Page title
     *
     * @var string
     */
    private $_title     = "ZNU LabsBlog";

    /**
     * Page body class
     * 
     * @var string 
     */
    private $_baseClass = 'page';

    public function __construct()
    {
        //set the full path to all property
        foreach ($this as $key => $property)
            if (is_array($property) && isset($property['path']))
                $this->{$key}['path'] = VPATH . $property['path'] . EXT;
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
     * Set page title
     *
     * @param string $title
     * @return \View
     */
    public function setTitle($title)
    {
        $this->_title = $this->_title . ' - ' . (string) $title;
        return $this;
    }

    /**
     * Retrieve page title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Retrieve Css by path
     *
     * @param string|array $path
     * @return string|boolean
     */
    public function getCss($path)
    {
        if (is_string($path))
            return '<link rel="stylesheet" href="' . $path . '" type="text/css" />';
        if (is_array($path))
        {
            $styles = NULL;
            foreach ($path as $value)
                $styles .= '<link rel="stylesheet" href="' . $value . '" type="text/css" />' . PHP_EOL;
            return $styles;
        }
        return false;
    }

    /**
     * Retrieve Js by path
     *
     * @param string|array $path
     * @return string|boolean
     */
    public function getJs($path)
    {
        if (is_string($path))
            return '<script type="text/javascript" src="' . $path . '"></script>';
        if (is_array($path))
        {
            $js = NULL;
            foreach ($path as $value)
                $js .= '<script type="text/javascript" src="' . $value . '"></script>' . PHP_EOL;
            return $js;
        }
        return false;
    }

    public function setBaseClass($class)
    {
        $this->_baseClass = $this->_baseClass . '-' . $class;
        return $this;
    }

    public function getBaseClass()
    {
        return $this->_baseClass;
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