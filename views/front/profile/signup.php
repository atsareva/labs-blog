<?php echo $this->getCss(Core::getBaseUrl() . "assets/uploadify/uploadify.css"); ?>
<?php echo $this->getJs(array(Core::getBaseUrl() . "/assets/uploadify/swfobject.js", Core::getBaseUrl() . '/assets/uploadify/jquery.uploadify.v2.1.4.min.js')); ?>

<script type='text/javascript'>
    $(function() {
        // validate signup form on keyup and submit
        $("#front_signup").validate({
            rules: {
                user_name: {
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
                user_name: {
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
    // --------------------------------------------------------------------
    $(function() {
        $('select[name="faculty_id"]').change(function() {
            $.ajax({
                url: "<?php echo Core::getBaseUrl() ?>ajax/loadDepartment",
                type: 'POST',
                dataType: 'json',
                data: 'faculty_id=' + $('select[name="faculty_id"] option:selected').val(),
                success: function(response) {
                    if (response.data)
                    {
                        var html = '<option value="0">-- Выбрать --</option>';
                        $.each(response.data, function(id, name) {
                            html += '<option value="' + id + '">' + name + '</option>';
                        })
                        $('select[name="department_id"]').html(html);
                    }
                }
            })
        })
    })
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
        <?php echo Security::secFtoken('userSignup') ?>
        <div class="fieldset">
            <div>
                <p>Логин <span class="star">*</span></p>
                <input type="text" name="user_name" value=""/>
            </div>
            <div>
                <p>Ваше ФИО</p>
                <input type="text" name="full_name" value=""/>
            </div>
            <div>
                <p>Статус</p>
                <select name='status_id'>
                    <option value='0'>-- Выбрать --</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value='<?php echo $status->id; ?>'><?php echo $status->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <p>Факультет</p>
                <select name='faculty_id'>
                    <option value='0'>-- Выбрать --</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value='<?php echo $faculty->id; ?>'><?php echo $faculty->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <p>Кафедра</p>
                <select name='department_id'>
                    <option value='0'>-- Выбрать --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value='<?php echo $department->id; ?>'><?php echo $department->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <p>E-mail <span class="star">*</span></p>
                <div class="input-prepend">
                    <span class="add-on">@</span>
                    <input type="text" name="email" value=""/>
                </div>
            </div>
            <div>
                <p>Skype</p>
                <input type="text" name="skype" value=""/>
            </div>
            <div>
                <p>Телефон</p>
                <input type="text" name="phone" value=""/>
            </div>
            <div>
                <p>Пароль <span class="star">*</span></p>
                <input id="pass" type="password" name="pass" value=""/>
            </div>
            <div>
                <p>Подтвердите пароль <span class="star">*</span></p>
                <input type="password" name="confirm_pass" value=""/>
            </div>
            <input type="hidden" id="attachment" name="photo" value=""/>
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