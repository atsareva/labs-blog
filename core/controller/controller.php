<?php

abstract class Controller
{

    /**
     * View instance
     *
     * @var object
     */
    public $_view;

    /**
     * Redirecting to location
     *
     * @param string $uri
     * @param string $method
     * @param int $httpResponseCode
     */
    public function redirect($uri = '', $method = 'location', $httpResponseCode = 302)
    {
        $uri = Core::getBaseUrl() . $uri;
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
