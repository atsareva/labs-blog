<script type="text/javascript">
  // --------------------------------------------------------------------  
     $(function(){
        $('#save').click(function(){
            
            if ($("#install_site").valid())
            {
                $('#install_site').submit();
            }
            else{
                var err=$("#install_site").children().find('label.error')
                $.each(err, function(i, olo){
                    $('input[name='+olo.htmlFor+']').parent().addClass('control-group error');
                })
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        // validate signup form on keyup and submit
        $("#install_site").validate({
            rules: {
                site_name: {
                    required: true
                },
                user_email: {
                    required: true,
                    email:true
                },
                login:{
                  required:true  
                },
                pass: {
                    required: true
                },
                confirm_pass: {
                    required: true,
                    equalTo: $('#pass')
                }
            },
            success: function(label) {
                label.removeClass('error').addClass("valid");
                $('input[name='+label[0].htmlFor+']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
</script>
<div class="span8 content-install"> 
    <div class="install-steps">
        <div class="row">
            <div class="span4">
                Конфигурация сайта.       
            </div>
            <div class="span3">
                <a class="btn btn-small btn-info" href="install.php?configuration">
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
                    <h4>Название сайта</h4>
                </div>
                <form id="install_site" method="post">
                    <input type="hidden" name="create_database" value="1" />
                    <table class="table">
                        <tr>
                            <td class="span3">
                                Название сайта <span class="star">*</span> 
                            </td>
                            <td>
                                <input type="text" name="site_name" value="<?php if (isset($_SESSION['users']['site_name']))
    echo $_SESSION['users']['site_name']; ?>" />
                            </td>
                        </tr>
                    </table>
                    <hr/>
                    <div style="margin: 10px;">
                        <h4>E-mail и пароль администратора.</h4>
                        Введите e-mail адрес. Это будет e-mail адрес Суперадминистратора сайта.
                        Введите новый пароль и подтверждение пароля в соответствующие поля ниже. 
                        Введенные вами данные будут логином и паролем, 
                        которые вы сможете использовать для авторизации после завершения установки.
                    </div>
                    <table class="table">
                        <tr>
                            <td  class="span3"> 
                                Ваш E-mail <span class="star">*</span>  
                            </td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">@</span>
                                    <input type="text" style="width: 182px;" name="user_email" value="<?php if (isset($_SESSION['users']['email']))
                                           echo $_SESSION['users']['email']; ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Логин администратора <span class="star">*</span>  
                            </td>
                            <td>
                                <input type="text" name="login" value="<?php if (isset($_SESSION['users']['login']))
                                               echo $_SESSION['users']['login']; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Пароль администратора <span class="star">*</span>  
                            </td>
                            <td>
                                <input type="password" name="pass" id="pass" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                Подтверждение пароля <span class="star">*</span>  
                            </td>
                            <td>
                                <input type="password" name="confirm_pass" value="" />
                            </td>
                        </tr>
                    </table>
                </form>
                <hr/>
            </div>
        </div>
    </div>
</div>
