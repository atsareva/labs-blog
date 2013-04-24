<?php echo $this->getCss(Core::getBaseUrl() . "assets/uploadify/uploadify.css"); ?>
<?php echo $this->getJs(array(Core::getBaseUrl() . "/assets/uploadify/swfobject.js", Core::getBaseUrl() . '/assets/uploadify/jquery.uploadify.v2.1.4.min.js')); ?>

<script type='text/javascript'>
    $(function() {
        // validate signup form on keyup and submit
        $("#front_signup").validate({
            rules: {
                login: {
                    required: true
                },
                email: {
                    required: true,
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
            messages: {
                login: {
                    required: "Это обязательное поле!"
                },
                email: {
                    required: "Это обязательное поле!",
                    email: "Пожалуйста, введите правильный e-mail адрес!"
                },
                pass: {
                    required: "Это обязательное поле!"
                },
                confirm_pass: {
                    required: "Это обязательное поле!",
                    equalTo: "Значения полей 'Пароль' и 'Подтвердите пароль' должны совпадать!"
                },
            },
            success: function(label) {
                label.removeClass('error').addClass("valid");
                $('input[name=' + label[0].htmlFor + ']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        $("#photo").uploadify({
            'uploader': '<?php echo Core::getBaseUrl() ?>assets/uploadify/uploadify.swf',
            'script': '<?php echo Core::getBaseUrl() ?>assets/uploadify/uploadify.php',
            'cancelImg': '<?php echo Core::getBaseUrl() ?>assets/uploadify/cancel.png',
            'folder': '/assets/upload',
            'multi': false,
            'auto': true,
            'removeCompleted': false,
            'scriptAccess': 'always',
            'checkScript': '/assets/uploadify/check.php',
            'fileDesc': 'jpg;png;gif;jpeg',
            'fileExt': '*.jpg;*.png;*.gif;*.jpeg',
            'onError': function(event, ID, fileObj, errorObj) {
                alert('<p>' + errorObj.type + ' Error: ' + errorObj.info + '</p>');
            },
            'onSelect': function() {
                //$(".save").prop("disabled", true);
            },
            'onComplete': function(event, ID, fileObj, response, data) {
                if (response == 1)
                {
                    $("#photo").uploadifyCancel(ID);
                    alert('Превишен максимальный размер загрузки файла!');
                }
                else
                {
                    $('#attachment').val(response);
                    $('.thumbnail img').attr('src', "<?php echo Core::getBaseUrl() ?>assets/resize/timthumb.php?src=" + response + "&h=310&w=310&zc=1")

                }
            },
            'onCancel': function(event, ID, fileObj, data, remove, clearFast) {
                $('input[name=attachments]').val('');
            }
        });
    });
</script>
<h2>Регистрация</h2>
<div class="separator"></div>

<?php if (($error)): ?>
    <div class="alert alert-error">
        <strong>Ошибка! </strong><?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="signup">
    <div class="photo">
        <a class="thumbnail" href="" onclick="return false;">
            <?php if (isset($result['photo']) && !empty($result['photo'])): ?>
                <img alt="" src="<?php echo Core::getBaseUrl() ?>assets/resize/timthumb.php?src=<?php echo $result['photo']; ?>&h=200&w=200&zc=1" />
            <?php else: ?>
                <img alt="" src="<?php echo Core::getBaseUrl() ?>assets/img/default.png" />
            <?php endif; ?>
        </a>
        <input id="photo" class="input-file" type="file"/>
        <div class="clearfix"></div>
    </div>
    
    <form id="front_signup" method="post" action="<?php echo Core::getBaseUrl() ?>profile/signup">
        <div class="fieldset">
            <div>
                <p>Логин <span class="star">*</span></p>
                <input type="text" name="login" value=""/>
            </div>
            <div>
                <p>E-mail <span class="star">*</span></p>
                <input type="text" name="email" value=""/>
            </div>
            <div>
                <p>Пароль <span class="star">*</span></p>
                <input id="pass" type="password" name="pass" value=""/>
            </div>
            <div>
                <p>Подтвердите пароль <span class="star">*</span></p>
                <input type="password" name="confirm_pass" value=""/>
            </div>
            <input type="hidden" id="attachment" name="attachment" value=""/>
        </div>
        <div class='button-set'>
            <div>
                <button class="btn btn-small btn-info" title='Войти'>
                    <i class="icon-user icon-white"></i>
                    Зарегистрироваться
                </button>
            </div>
        </div>
    </form>
</div>