<?php

require_once CORE_PATH . 'view/view' . EXT;

class View_Admin extends View
{

    /**
     * Main page template
     *
     * @var array
     */
    public $_template = array(
        'path' => "admin/page/one-column",
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
        'path' => 'admin/page/html/head',
        'data' => array());

    /**
     * Page header block
     *
     * @var array
     */
    public $_header = array(
        'path' => 'admin/page/html/header',
        'data' => array());

    /**
     * Page footer block
     *
     * @var array
     */
    public $_footer = array(
        'path' => 'admin/page/html/footer',
        'data' => array());

    /**
     * Page navbar block
     *
     * @var array
     */
    public $_navBar = array(
        'path' => 'admin/page/html/navbar',
        'data' => array());

    /**
     * Page navbar menu block
     *
     * @var array
     */
    public $_navBarMenu = array(
        'path' => 'admin/page/html/navbar-menu',
        'data' => array());

    /**
     * Page nabigation block
     *
     * @var array
     */
    public $_mainNav = array(
        'path' => 'admin/page/html/main-nav',
        'data' => array());

    /**
     * Page title
     *
     * @var string
     */
    private $_title = "ZNU LabsBlog Admin";

    /**
     * Page body class
     * 
     * @var string 
     */
    private $_baseClass = 'page-admin';

}