<?php

require_once CORE_PATH . 'view/view' . EXT;

class View_Front extends View
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
    protected $_title     = "ZNU LabsBlog";

    /**
     * Page body class
     * 
     * @var string 
     */
    protected $_baseClass = 'page';

}