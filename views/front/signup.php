<script type="text/javascript">
    // --------------------------------------------------------------------
    $(function(){
        $('#signup').click(function(){
            if ($('#front_signup').valid())
            {
                $('#front_signup').submit();
            }
            else{
                var err=$("#front_signup").children().find('label.error')
                $.each(err, function(i, olo){
                    $('input[name='+olo.htmlFor+']').parent().addClass('control-group error');
                })
            }
        }) 
    });
    // --------------------------------------------------------------------
    $(function() {
        // validate signup form on keyup and submit
        $("#front_signup").validate({
            rules: {
                login: {
                    required: true
                },
                email: {
                    required:  true,
                    email: true
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
    // --------------------------------------------------------------------
</script>
<div class="span8"> 
    <div class="row material">
        <center>
            <h2 style="margin: 0 0 30px;">
               Регистрация
            </h2>
        </center>
        <br/>
        <br/>
        <br/>
        <form id="front_signup" method="post">
            <table class="offset1">
                <tr>
                    <td class="span2">
                        Логин <span class="star">*</span> 
                    </td>
                    <td>
                        <input type="text" name="login" value=""/>
                    </td>
                </tr>
                <tr>
                    <td class="span2">
                        E-mail <span class="star">*</span> 
                    </td>
                    <td>
                        <input type="text" name="email" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Пароль <span class="star">*</span> 
                    </td>
                    <td>
                        <input id="pass" type="password" name="pass" value=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Подтвердите пароль <span class="star">*</span> 
                    </td>
                    <td>
                        <input type="password" name="confirm_pass" value=""/>
                    </td>
                </tr>
            </table>
        </form>
        <br/>
        <div class="row">
            <div style="margin: 5px 0px 0 324px;">
                <a id ="signup" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-user icon-white"></i>
                    Зарегистрироваться
                </a>
            </div>
        </div>
    </div>
</div>