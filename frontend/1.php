<?php echo $content ?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>ZNU Labs Blog - Home</title>

        <link rel="stylesheet" href="<?php echo Core::getBaseUrl() ?>assets/css/bootstrap.css" type="text/css" />
        <!--<link rel="stylesheet" href="skin/css/bootstrap-responsive.css" type="text/css" />-->
        <link rel="stylesheet" href="<?php echo Core::getBaseUrl() ?>assets/css/docs.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo Core::getBaseUrl() ?>assets/css/style.css" type="text/css"/>

        <script type="text/javascript" src="<?php echo Core::getBaseUrl() ?>assets/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="<?php echo Core::getBaseUrl() ?>assets/js/bootstrap.js"></script>
    </head>
    <body class="found404">
        <!--======================  NavBar  ======================-->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class="span2 offset10 login">
                        <a href="#">SignUp</a> | <a href="#">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!--======================  Header  ======================-->
        <header class="header">
            <div class="container">
                <div class="brand">
                    <a href="#"><h1>ZNU <span>LabsBlog</span></h1></a>
                </div>
            </div>
        </header>
        <div class="clearfix"></div>
        <!--======================  Main Container  ======================-->
        <div class="container">
            <div class="container-shadow">
                <div class="row-fluid found404-content alignCenter">
                    <div>404</div>
                    <div>Запрошенная страница не найдена. Вернуться на <a href="<?php echo Core::getBaseUrl() ?>">главную</a>.</div>
                </div>
            </div>
        </div>
        <!--======================  Footer  ======================-->
        <footer class="footer">
            <div class="container">
                <div class="my-brand">
                    <i class="icon-bookmark icon-white"></i>
                    Simple<span>CMS</span>
                </div>
                ZNU LabsBlog &copy; 2013
            </div>
        </footer>

        <script type="text/javascript">
            $(function(){
                $(".nav.nav-list .parent").append("<i class='icon-chevron-right'></i>");
            })
        </script>
    </body>
</html>
