<script type="text/javascript">
    // --------------------------------------------------------------------
    $(function(){
        $('#login').click(function(){
            if ($('#front_login').valid())
            {
                $('#front_login').submit();
            }
            else{
                var err=$("#front_login").children().find('label.error')
                $.each(err, function(i, olo){
                    $('input[name='+olo.htmlFor+']').parent().addClass('control-group error');
                })
            }
        }) 
    });
    // --------------------------------------------------------------------
    $(function() {
        // validate signup form on keyup and submit
        $("#front_login").validate({
            rules: {
                login: {
                    required: true
                },
                pass: {
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
<div class="span8"> 
    <div class="row material">
        <center>
            <h2 style="margin: 0 0 30px;">
                Вход на сайт
            </h2>
        </center>
        <br/>
        <br/>
        <?php if (isset($error)): ?>
        <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; margin: 0px 100px 0px 100px;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><?php echo $error; ?></p></div>
        <?php endif; ?>
        <br/>
        <form id="front_login" method="post">
            <table class="offset1">
                <tr>
                    <td class="span2">
                        Логин <span class="star">*</span> 
                    </td>
                    <td>
                        <input type="text" name="login" value="<?php if (isset($result['user_name']))
            echo $result['user_name']; ?>"/>
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
            </table>
        </form>
        <br/>
        <div class="row">
            <div class="span2" style="margin: 14px 5px 5px 242px;">
                <a href="/profile/signup">Не зарегистрированы?</a>
            </div>
            <div style="margin: 5px 0px 0 400px;">
                <a id ="login" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-user icon-white"></i>
                    Войти
                </a>
            </div>
        </div>
    </div>
</div>