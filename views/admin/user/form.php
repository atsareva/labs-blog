<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving() {
        if ($("#form_user").valid())
        {
            $('#form_user').submit();
        }
        else {
            var err = $("#form_user").children().find('label.error')
            $.each(err, function(i, element) {
                $('input[name=' + element.htmlFor + ']').parent().addClass('control-group error');
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
        // validate signup form on keyup and submit
        $("#form_user").validate({
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
</script>
<div class="content-head">
    <div class="row">
        <div class="span6">
            Пользователи: <?php echo $head ?>
        </div>
        <div class="span3 offset2">
            <a rel="tooltip" title="Сохранить" id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-ok icon-white"></i>
            </a>
            <a rel="tooltip" title=" Сохранить и закрыть" id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-folder-close icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin/users">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<form id="form_user" action="<?php echo $userUrl; ?>" method="post">
    <input type="hidden" name="save_exit" value="0"/>
    <div class="row">
        <div class="span12">
            <div class="well">
                <h4><?php echo $head ?></h4>
                <hr/>
                <div class="fieldset">
                    <div>
                        <p>Логин <span class="star">*</span></p>
                        <input type="text" name="user_name" value="<?php if (isset($user)):?><?php echo $user->getUserName()?><?php endif;?>"/>
                    </div>
                    <div>
                        <p>Пароль <span class="star">*</span></p>
                        <input id="pass" type="password" name="pass" value=""/>
                    </div>
                    <div>
                        <p>Повтор пароля <span class="star">*</span></p>
                        <input type="password" name="confirm_pass" value=""/>
                    </div>
                    <div>
                        <p>E-mail <span class="star">*</span></p>
                        <div class="input-prepend">
                            <span class="add-on">@</span>
                            <input type="text" name="email" value="<?php if (isset($user)):?><?php echo $user->getEmail()?><?php endif;?>"/>
                        </div>
                    </div>
                    <div>
                        <p>Статус <span class="star">*</span></p>
                        <select name='status_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($statuses as $status): ?>
                                <option <?php if (isset($user) && $status->id == $user->getStatusId()): ?> selected="selected" <?php endif; ?> value='<?php echo $status->id; ?>'><?php echo $status->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <p>Доступ <span class="star">*</span></p>
                        <select name='access_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($access as $accessValue): ?>
                                <option <?php if (isset($user) && $accessValue->id == $user->getAccessId()): ?> selected="selected" <?php endif; ?> value='<?php echo $accessValue->id; ?>'><?php echo $accessValue->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

