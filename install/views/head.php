<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!-- #012340 blue, #343434 grey, #6A8C37-->
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>SimpleCMS</title>

        <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/docs.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/prettify.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/jquery-ui-1.8.18.custom.css" type="text/css"/>

        <script type="text/javascript" src="assets/js/prettify.js"></script>
        <script type="text/javascript" src="assets/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery-ui-1.8.18.custom.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery.validate.js"></script>
    </head>
    <body onload="prettyPrint()">
        <script type="text/javascript">
            // --------------------------------------------------------------------
            function reload_content()
            {
                $('#reload').click(function(){
                    $('#reload').html('<img style="margin: 161px 0 0 302px;" src="install/images/ajax-loader(2).gif"/>');
                    $.ajax({
                        type: "POST",
                        dataType: "html",
                        url: "ajax.php",
                        data: 'reload='+1,
                        success: function(data)
                        {
                            $('#reload').html(data);
                        }
                    });
                });
            };
            // --------------------------------------------------------------------
        </script>
        <div id="olo"></div>
        <div class="navbar navbar-fixed-top my">
            <div class="navbar-inner">
                <div class="container">
                    <div class="head">SimpleCMS. Установка.</div>
                    <a class="my-brand" href="" onclick="return false;"><i class="icon-bookmark"></i>Simple<span class="brand_A">CMS</span></a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="span4 left-install">

                    <table class="table-install-A table table-bordered">
                        <thead>
                            <tr>
                                <th>
                        <h2>Шаги</h2>
                        </th>
                        </tr>
                        </thead>
                        <tr>
                            <td <?php if (isset($hover_step1))
    echo "class='install-menu-active'"; ?>>
                                <span>1.</span> Начальная проверка
                            </td>
                        </tr>
                        <tr>
                            <td <?php if (isset($hover_step2))
                                    echo "class='install-menu-active'"; ?>>
                                <span>2</span>. Конфигурация БД
                            </td>
                        </tr>
                        <tr>
                            <td <?php if (isset($hover_step3))
                                    echo "class='install-menu-active'"; ?>>
                                <span>3</span>. Конфигурация сайта
                            </td>
                        </tr>
                        <tr>
                            <td <?php if (isset($hover_step4))
                                    echo "class='install-menu-active'"; ?>>
                                <span>4</span>. Завершение установки
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="reload">
