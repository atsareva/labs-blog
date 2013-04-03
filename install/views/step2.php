<script type="text/javascript">
    // --------------------------------------------------------------------
    $(function(){
        $('#save').click(function(){
            
            if ($("#install_database").valid())
            {
                $('#install_database').submit();
            }
            else{
                var err=$("#install_database").children().find('label.error')
                $.each(err, function(i, olo){
                    $('input[name='+olo.htmlFor+']').parent().addClass('control-group error');
                })
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        // validate signup form on keyup and submit
        $("#install_database").validate({
            rules: {
                server_name: {
                    required: true
                },
                user_name: {
                    required: true
                },
                pass: {
                    required: true
                },
                database_name: {
                    required: true
                }
            },
            success: function(label) {
                label.removeClass('error').addClass("valid");
                $('input[name='+label[0].htmlFor+']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
    // --------------------------------------------------------------------
</script>
<div class="span8 content-install"> 
    <div class="install-steps">
        <div class="row">
            <div class="span4">
                Конфигурация базы данных.       
            </div>
            <div class="span3">
                <a class="btn btn-small btn-info" href="/">
                    <i class="icon-arrow-left icon-white"></i>
                    Назад
                </a>
                <a id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                    Далее
                    <i class="icon-arrow-right icon-white"></i>
                </a>
            </div>
        </div>

    </div>
    <div class="install-check">
        <div class="row">
            <div class="span8">
                <div  style="margin: 10px;">
                    &nbsp;&nbsp;&nbsp;На этой странице вводится информация, 
                    необходимая для создания базы данных.
                    Требуется предварительно создать базу данных и только после этого начинать установку SimpleCMS!
                </div>
                <form id="install_database" method="post" action="">
                    <input type="hidden" name="check_database" value="1" />
                    <table class="table">
                        <tr>
                            <td class="span3">
                                Имя сервера базы данных <span class="star">*</span> 
                                <input type="text" name="server_name" value="<?php if (isset($_SESSION['db']['db_host']))
    echo $_SESSION['db']['db_host']; ?>" />
                            </td>
                            <td class="install-database-info">
                                <em>Это обычно "localhost".</em>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Имя пользователя <span class="star">*</span>  
                                <input type="text" name="user_name" value="<?php if (isset($_SESSION['db']['db_user']))
                                           echo $_SESSION['db']['db_user']; ?>" />
                            </td>
                            <td class="install-database-info">
                                <em>Введите имя пользователя базы данных.</em>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Пароль <span class="star">*</span>  
                                <input type="password" name="pass" value="<?php if (isset($_SESSION['db']['db_pass']))
                                           echo $_SESSION['db']['db_pass']; ?>" />
                            </td>
                            <td class="install-database-info">
                                <em>Введите пароль пользователя MySQL.</em>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Имя базы данных <span class="star">*</span>  
                                <input type="text" name="database_name" value="<?php if (isset($_SESSION['db']['db_name']))
                                           echo $_SESSION['db']['db_name']; ?>" />
                            </td>
                            <td class="install-database-info">
                                <em>Введите имя созданной базы данных.</em>
                            </td>
                        </tr>
                    </table>
                </form>
                <hr/>
            </div>
        </div>
    </div>
</div>
