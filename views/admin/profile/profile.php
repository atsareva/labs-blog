<?php echo $this->getCss(Core::getBaseUrl() . "assets/uploadify/uploadify.css"); ?>
<?php echo $this->getJs(array(Core::getBaseUrl() . "/assets/uploadify/swfobject.js", Core::getBaseUrl() . '/assets/uploadify/jquery.uploadify.v2.1.4.min.js')); ?>

<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving() {
        if ($("#profile").valid())
        {
            $('#profile').submit();
        }
        else {
            var err = $("#profile").children().find('label.error')
            $.each(err, function(i, olo) {
                $('input[name=' + olo.htmlFor + ']').parent().addClass('control-group error');
            })
        }
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#save').click(function() {
            saving();
        })
        $('#save_exit').click(function() {
            $('input[name=save_exit]').val(1);
            saving();
        })
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
        // validate signup form on keyup and submit
        $("#profile").validate({
            rules: {
                user_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                confirm_pass: {
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
                confirm_pass: {
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
     // --------------------------------------------------------------------
</script>

<div class="content-head">
    <div class="row">
        <div class="span4">
            Мой профиль.
        </div>
        <div class="span5 offset2">
            <a id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-check icon-white"></i>Сохранить
            </a>
            <a id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-folder-close icon-white"></i>Сохранить и закрыть
            </a>
            <a class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin">
                <i class="icon-home icon-white"></i>Отмена
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="span6">
        <div class="well">
            <h4>Параметры моего профиля</h4>
            <hr/>

            <?php if (($error)): ?>
                <div class="alert alert-error">
                    <strong>Ошибка! </strong><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form id="profile" action="<?php echo Core::getBaseUrl() ?>auth/profile" method="post">
                <input type="hidden" name="save_exit" value="0"/>
                <div class="fieldset">
                    <div>
                        <p>Логин <span class="star">*</span></p>
                        <input type="text" name="user_name" value="<?php echo $user->getUserName() ?>"/>
                    </div>
                    <div>
                        <p>Ваше ФИО</p>
                        <input type="text" name="full_name" value="<?php if ($user->getFullName()):?><?php echo $user->getFullName() ?><?php endif; ?>"/>
                    </div>
                    <div>
                        <p>Пароль</p>
                        <input id="pass" type="password" name="pass" value=""/>
                    </div>
                    <div>
                        <p>Повтор пароля</p>
                        <input type="password" name="confirm_pass" value=""/>
                    </div>
                    <div>
                        <p>E-mail <span class="star">*</span></p>
                        <div class="input-prepend">
                            <span class="add-on">@</span>
                            <input type="text" name="email" style="width: 182px;" value="<?php echo $user->getEmail() ?>"/>
                        </div>
                    </div>
                    <div>
                        <p>Skype</p>
                        <input type="text" name="skype" value="<?php if ($user->getSkype()):?><?php echo $user->getSkype() ?><?php endif; ?>"/>
                    </div>
                    <div>
                        <p>Телефон</p>
                        <input type="text" name="phone" value="<?php if ($user->getPhone()):?><?php echo $user->getPhone() ?><?php endif; ?>"/>
                    </div>
                    <div>
                        <p>Факультет</p>
                        <select name='faculty_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($faculties as $faculty): ?>
                                <option <?php if ($faculty->id == $user->getFacultyId()): ?> selected="selected" <?php endif; ?> value='<?php echo $faculty->id; ?>'><?php echo $faculty->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <p>Кафедра</p>
                        <select name='department_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($departments as $department): ?>
                                <option <?php if ($department->id == $user->getDepartmentId()): ?> selected="selected" <?php endif; ?> value='<?php echo $department->id; ?>'><?php echo $department->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <p>Статус</p>
                        <select name='status_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($statuses as $status): ?>
                                <option <?php if ($status->id == $user->getStatusId()): ?> selected="selected" <?php endif; ?> value='<?php echo $status->id; ?>'><?php echo $status->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <p>Дата регистрации</p>
                        <?php if ($user->getRegisterDate()): ?>
                            <?php echo date('d/m/Y H:i:s', $user->getRegisterDate()) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p>Дата последнего входа</p>
                        <?php if ($user->getLastLogin()): ?>
                            <?php echo date('d/m/Y H:i:s', $user->getLastLogin()) ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" id="attachment" name="photo" value="<?php if ($user->getPhoto()):?><?php echo $user->getPhoto() ?><?php endif; ?>"/>
                </div>
            </form>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <h4>Загрузка аватара</h4>
            <hr/>
            <div class="photo">
                <ul class="thumbnails">
                    <li>
                        <a class="thumbnail" href="" onclick="return false;">
                            <?php if ($user->getPhoto()): ?>
                                <img alt="" src="<?php echo Core::getBaseUrl() ?>assets/resize/timthumb.php?src=<?php echo $user->getPhoto() ?>&h=300&w=300&zc=1" />
                            <?php else: ?>
                                <img alt="" src="<?php echo Core::getBaseUrl() ?>assets/img/default.png" />
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                <input id="photo" class="input-file" type="file"/>
            </div>
        </div>
    </div>
</div>
