<script type='text/javascript'>
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
                $('input[name=' + label[0].htmlFor + ']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
    // --------------------------------------------------------------------
</script>
<h2>Вход на сайт</h2>
<div class="separator"></div>

<?php if (($error)): ?>
    <div class="alert alert-error">
        <strong>Ошибка! </strong><?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="login">
    <form id="front_login" method="post" action="<?php echo Core::getBaseUrl() ?>profile/login">
        <div class="fieldset">
            <div>
                <p>Логин <span class="star">*</span></p>
                <input type="text" name="login" value=""/>
            </div>
            <div>
                <p>Пароль <span class="star">*</span></p>
                <input id="pass" type="password" name="pass" value=""/>
            </div>
        </div>
        <div class='button-set'>
            <div>
                <a href="<?php echo Core::getBaseUrl() ?>profile/signup">Не зарегистрированы?</a>
            </div>
            <div>
                <button class="btn btn-small btn-info" title='Войти'>
                    <i class="icon-user icon-white"></i>
                    Войти
                </button>
            </div>
        </div>
    </form>
</div>