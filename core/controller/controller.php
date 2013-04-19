<?php

require_once CORE_PATH . 'view/view' . EXT;

abstract class Controller
{

    /**
     * View instance
     *
     * @var object
     */
    public $_view;

    public function __construct()
    {
        $this->_view = new View();
    }

    /**
     * Redirecting to location
     *
     * @param string $uri
     * @param string $method
     * @param int $httpResponseCode
     */
    public function redirect($uri = '', $method = 'location', $httpResponseCode = 302)
    {
        if (!preg_match('#^https?://#i', $uri))
        {
            //$uri = site_url($uri);
        }

        switch ($method)
        {
            case 'refresh' : header("Refresh:0;url=" . $uri);
                break;
            default : header("Location: " . $uri, TRUE, $httpResponseCode);
                break;
        }
        exit;
    }

}

?>
