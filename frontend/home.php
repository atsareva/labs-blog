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
    <body>
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
                <div class="row-fluid">
                    <div class="span4 bs-docs-sidebar">
                        <ul class="nav nav-list bs-docs-sidenav affix-top">
                            <li>
                                <a href="#">
                                    <span class="parent">Математическая статистика</span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="#">
                                    &emsp;Модуль 1
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    &emsp;&emsp;&emsp;Практическое задание №1
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    &emsp;&emsp;Модуль 2
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="parent">Математический анализ</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    &emsp;&emsp;Модуль 1
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="span8">
                        <h2>Модуль 1</h2>
                        <div class="separator"></div>
                        <p class="post-date">09/05/2012</p>
                        <h5>МЕТОДИ СТАТИСТИЧНОГО ВИСНОВКУ</h5>
                        <p>Поточний контроль з дисципліни «Математичні методи в психолого-педагогічних дослідженнях» 
                            проводиться після закінчення кожного модулю у видіписьмових контрольних робіт. 
                            Він необхідний для діагностування ходу дидактичного процесу, виявлення динаміки останнього, 
                            зіставлення реально досягнутих на окремих етапах результатів із запроектованими.</p>
                        <div class="author-info-small">
                            <a href="author.html">
                                <i class="icon-user"></i>
                                <span>admin</span>
                            </a>
                        </div>
                        <div class="separator"></div>
                        <ul class="list-article">
                            <li>
                                <a href="#">Запитання для підготовки студентів до контролю знань з другого модуля</a>
                            </li>
                            <li>
                                <a href="#">ПРИКЛАД ВАРІАНТУ</a>
                            </li>
                            <li>
                                <a href="#">КРИТЕРІЇ ОЦІНЮВАННЯ ЗНАНЬ СТУДЕНТІВ</a>
                            </li>
                            <li>
                                <a href="#">ПЕРЕЛІК ДИДАКТИЧНОГО МАТЕРІАЛУ</a>
                            </li>
                        </ul>
                    </div>
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
