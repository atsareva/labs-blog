<head>
    <meta charset="utf-8" />
    <title><?php echo $this->getTitle(); ?></title>
    <?php

    echo $this->getCss(array(
        Core::getBaseUrl() . "assets/css/bootstrap.css",
        Core::getBaseUrl() . "assets/css/docs.css",
        Core::getBaseUrl() . "assets/css/style_admin.css"
    ));
    
    echo $this->getJs(array(
        Core::getBaseUrl() . "assets/js/jquery-1.7.1.min.js",
        Core::getBaseUrl() . "assets/js/bootstrap.js",
        Core::getBaseUrl() . "assets/js/jquery.validate.js"
    ));
    ?>
</head>