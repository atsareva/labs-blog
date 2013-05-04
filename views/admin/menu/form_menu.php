<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving() {
        if ($("#form_menu").valid())
        {
            $('#form_menu').submit();
        }
        else {
            var err = $("#form_menu").children().find('label.error')
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
        // validate signup form on keyup and submit
        $("#form_menu").validate({
            rules: {
                title: {
                    required: true
                },
                alias: {
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

<div class="content-head">
    <div class="row">
        <div class="span6">
            Менеджер меню: <?php echo $head ?>
        </div>
        <div class="span3 offset2">
            <a rel="tooltip" title="Сохранить" id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-ok icon-white"></i>
            </a>
            <a rel="tooltip" title=" Сохранить и закрыть" id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-folder-close icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin/menu">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<form id="form_menu" action="<?php echo $menuUrl ?>" method="post">
    <input type="hidden" name="save_exit" value="0"/>
    <div class="row">
        <div class="span12">
            <div class="well">
                <h4><?php echo $head ?></h4>
                <hr/>
                <div class="fieldset">
                    <div>
                        <p>Заголовок <span class="star">*</span></p>
                        <input name="title" type="text" value="<?php if (isset($menu)):?><?php $menu->getTitle()?><?php endif ?>"/>
                    </div>
                    <div>
                        <p>Показывать заголовок <span class="star">*</span></p>
                        <span style="margin-right: 50px;">
                            <input type="radio" <?php if (isset($menu) && $menu->getShowTitle() == 0): ?>checked="checked"<?php endif ?> name="show_title" value="0"/>Нет
                        </span>
                        <input type="radio" <?php if (isset($menu) && $menu->getShowTitle() == 1): ?>checked="checked"<?php endif ?> name="show_title" value="1"/>Да
                    </div>
                    <div>
                        <p>Состояние</p>
                        <select name="status">
                            <option value="0" <?php
                            if (isset($result['status']) && $result['status'] == 0)
                                echo 'selected="selected"';
                            ?>>Не опубликовано</option>
                            <option value="1" <?php
                            if (isset($result['status']) && $result['status'] == 1)
                                echo 'selected="selected"';
                            ?>>Опубликовано</option>
                        </select>
                    </div>
                    <div>
                        <p>Позиция</p>
                        <input name="position" value="<?php if (isset($menu) && $menu->getPosition()):?><?php $menu->getPosition()?><?php endif ?>"/>
                    </div>
                    <div>
                        <p>Доступ</p>
                        <select name='access_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($access as $accessValue): ?>
                                <option <?php if (isset($menu) && $accessValue->id == $menu->getAccessId()): ?> selected="selected" <?php endif; ?> value='<?php echo $accessValue->id; ?>'><?php echo $accessValue->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>