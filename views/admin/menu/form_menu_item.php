<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_choose(element)
    {
        $('#dialog-attach .table').find('tbody').empty();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Core::getBaseUrl() ?>ajax/loadAttachment",
            data: 'attach=' + element,
            success: function(data) {
                if (data)
                {
                    var add_user;
                    $.each(data, function(i, value) {
                        add_user = "<td><input type='radio' class='" + value.alias + "' name='elem_id' value=" + value.id + "></td>"
                        add_user += "<td><font color='#4AA2D9'>" + value.alias + "</font></td>";
                        add_user += "<td>" + value.title + "</td>";
                        $('#dialog-attach .table').find('tbody')
                         .append($('<tr class="' + value.id + '">')
                         .append($(add_user)));
                    });


                    $("#dialog-attach").dialog({
                        height: 400,
                        width: 400,
                        modal: true,
                        buttons: {
                            "Выбрать": function() {
                                if ($("input[name=elem_id]:checked").length)
                                {
                                    $("#dialog-attach").find('label').empty();
                                    $('input[name=path]').val('home?page=' + element + '&id=' + $("input[name=elem_id]:checked")[0].defaultValue);
                                    $(this).dialog("close");
                                }
                                else
                                {
                                    $("#dialog-attach").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали элемент.</p></div>');
                                }
                            },
                            "Отменить": function() {
                                $("#dialog-attach").find('label').empty();
                                $(this).dialog("close");
                            }
                        }
                    });
                }
            }
        });
    }
    // --------------------------------------------------------------------
    function saving() {
        if ($("#form_menu_item").valid())
        {
            $('#form_menu_item').submit();
        }
        else {
            var err = $("#form_menu_item").children().find('label.error')
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
        $("#form_menu_item").validate({
            rules: {
                title: {
                    required: true
                },
                alias: {
                    required: true
                },
                parent_id: {
                    required: true
                },
                for_index: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Это обязательное поле!"
                },
                alias: {
                    required: "Это обязательное поле!"
                },
                parent_id: {
                    required: "Это обязательное поле!"
                },
                for_index: {
                    required: "Это обязательное поле!"
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

<!-- dialog for attach material-->
<div id="dialog-attach" style="display: none" title="Выбрать элемент для связи">
    <label></label>
    <table class="table table-bordered table-striped">
        <colgroup>
            <col class="span1">
            <col class="span2">
            <col class="span4">
        </colgroup>
        <tbody></tbody>
    </table>
</div>
<!--end dialog-->

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
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin/menu/<?php echo $menuId ?>">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<form id="form_menu_item" action="<?php echo $menuUrl ?>" method="post">
    <input type="hidden" name="save_exit" value="0"/>
    <div class="row">
        <div class="span12">
            <div class="well">
                <h4><?php echo $head ?></h4>
                <hr/>
                <div class="fieldset">
                    <div>
                        <p>Заголовок <span class="star">*</span></p>
                        <input name="title" type="text" value="<?php if (isset($menuItem)): ?><?php echo $menuItem->getTitle() ?><?php endif ?>"/>
                    </div>
                    <div>
                        <p>Алиас <span class="star">*</span></p>
                        <input name="alias" type="text" value="<?php if (isset($menuItem)): ?><?php echo $menuItem->getAlias() ?><?php endif ?>"/>
                    </div>
                    <div>
                        <p>Родительский элемент <span class="star">*</span></p>
                        <select name="parent_id">
                            <option value="0">-- Корневой элемент --</option>
                            <?php if (count($menuItems) > 0): ?>
                                <?php foreach ($menuItems as $keyItem => $valueItem): ?>
                                    <?php $dash = NULL; ?>
                                    <?php for ($i = 0; $i < $valueItem['dash'] - 1; $i++): ?>
                                        <?php $dash.= '|----  '; ?>
                                    <?php endfor; ?>
                                    <option <?php if (isset($menuItem) && $menuItem->getParentId() == $keyItem): ?>selected="selected"<?php endif; ?> value="<?php echo $keyItem; ?>"><span class="menu-dash"><?php echo $dash; ?></span><?php echo $valueItem['title']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <p>Главная страница <span class="star">*</span></p>
                        <span style="margin-right: 50px;">
                            <input type="radio" <?php if ((isset($menuItem) && $menuItem->getForIndex() == 0) || !isset($menuItem)): ?>checked="checked"<?php endif ?> name="for_index" value="0"/>Нет
                        </span>
                        <input type="radio" <?php if (isset($menuItem) && $menuItem->getForIndex() == 1): ?>checked="checked"<?php endif ?> name="for_index" value="1"/>Да
                    </div>
                    <div>
                        <p>Состояние</p>
                        <select name="status">
                            <option value="0" <?php if (isset($menuItem) && $menuItem->getStatus() == 0): ?>selected="selected"<?php endif ?>>Не опубликовано</option>
                            <option value="1" <?php if (isset($menuItem) && $menuItem->getStatus() == 1): ?>selected="selected"<?php endif ?>>Опубликовано</option>
                        </select>
                    </div>
                    <div>
                        <p>Позиция</p>
                        <?php if (!isset($menuItem)): ?>
                            Настройка позиции будет доступна после сохранения элемента
                        <?php else: ?>
                            <select name="order_of">
                                <?php if (isset($orderOf)): ?>
                                    <?php if (count($orderOf) == 0): ?>
                                        <option selected="selected" value="<?php echo $menuItem->getId(); ?>">Первый элемент</option>
                                    <?php else: ?>
                                        <?php foreach ($orderOf as $orderItem): ?>
                                            <option <?php if ($menuItem->getId() == $orderItem->id): ?>selected="selected"<?php endif; ?> value="<?php echo $orderItem->id; ?>"><?php echo $orderItem->order_of . ' - ' . $orderItem->title; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p>Доступ</p>
                        <select name='access_id'>
                            <option value='0'>-- Выбрать --</option>
                            <?php foreach ($access as $accessValue): ?>
                                <option <?php if (isset($menuItem) && $accessValue->id == $menuItem->getAccessId()): ?> selected="selected" <?php endif; ?> value='<?php echo $accessValue->id; ?>'><?php echo $accessValue->description; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="attach">
                        <p>Прикрепить</p>
                        <input name="path" readonly value="<?php if (isset($menuItem)): ?><?php echo $menuItem->getPath() ?><?php endif ?>" />
                        <div>
                            <p>
                                <a rel="tooltip" title="Выбрать материал" class="btn btn-small btn-info" href="" onclick="dialog_choose('material');return false;"><i class="icon-ok icon-white"></i></a>
                                Материал
                            </p>
                            <p>
                                <a rel="tooltip" title="Выбрать категорию" class="btn btn-small btn-info" href=""  onclick="dialog_choose('category');return false;"><i class="icon-ok icon-white"></i></a>
                                Категория
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
