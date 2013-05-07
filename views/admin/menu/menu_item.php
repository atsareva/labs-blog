<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_menu_item()
    {
        $("#dialog-menu_item").dialog({
            height: 200,
            width: 400,
            modal: true,
            buttons: {
                "Отменить": function() {
                    {
                        $("#dialog-menu_item").find('label').empty();
                        $(this).dialog("close");
                    }
                }
            }
        });
    }
    ;
    // --------------------------------------------------------------------
    function public_menu_item(bool)
    {
        if ($("input[name^=menu_item]:checked").length == 0)
        {
            $("#dialog-menu_item").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');
            dialog_menu_item();
        }
        else
        {
            if (bool)
                var status = 'Опубликовано';
            else
                var status = 'Неопубликовано';
            var ids = '';
                $.each($("input[name^=menu_item]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#menu_item_' + $(element).val() + ' td.status').html(status);
                })
            $.post("<?php echo Core::getBaseUrl() ?>menu/publicMenuItem", {'ids': ids, 'bool': bool});
        }
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#edit').click(function() {
            if ($("input[name^=menu_item]:checked").length == 1)
                window.location = "<?php echo Core::getBaseUrl() ?>menu/editItem/" + $("input[name^=menu_item]:checked").val();
            else if ($("input[name^=menu_item]:checked").length > 1)
            {
                $("#dialog-menu_item").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');
                dialog_menu_item();
            }
            else
            {
                $("#dialog-menu_item").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');
                dialog_menu_item();
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        $('#trash_menu_item').click(function() {
           if ($("input[name^=menu_item]:checked").length == 0)
            {
                $("#dialog-menu_item").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');
                dialog_menu_item();
            }
            else
            {
                var ids = '';
                $.each($("input[name^=menu_item]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#menu_item_' + $(element).val()).hide();
                })
                $.post("<?php echo Core::getBaseUrl() ?>menu/removeItem", {'ids': ids});
            }
        });
    });
    // --------------------------------------------------------------------
    function for_index(id)
    {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Core::getBaseUrl() ?>ajax/forIndex",
            data: 'id=' + id,
            success: function(response) {
                if (response.id)
                {
                    var html = '<a href="" onclick="for_index(' + id + ');return false;">';
                    if (response.for_index == 0)
                        html += '<i class="icon-star-empty"></i>';
                    else
                        html += '<i class="icon-star"></i>';
                    html += '</a>';
                    $('#menu_item_' + response.id + ' td.for_index').html(html);
                }
            }
        });
    }
    // --------------------------------------------------------------------
<?php if (count($menuItems) > 0): ?>
        $(function() {
            $("#all_menu_item").tablesorter()
             .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
    // --------------------------------------------------------------------

</script>

<!-- dialog users-->
<div id="dialog-menu_item" style="display: none" title="Предупреждение!">
    <label></label>
</div>
<!--end dialog-->

<div class="content-head">
    <div class="row">
        <div class="span5">
            Менеджер пунктов меню
        </div>
        <div class="span5 offset1">
            <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>menu/createItem">
                <i class="icon-plus icon-white"></i>
            </a>
            <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-pencil icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_menu_item(1);
        return false;">
                <i class="icon-ok-circle icon-white"></i>
            </a>
            <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_menu_item(0);
        return false;">
                <i class="icon-remove-circle icon-white"></i>
            </a>
            <span class="border"></span>
            <a id="trash_menu_item" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-trash icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="span12">
        <div class="well">
            <table id="all_menu_item" class="tablesorter table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="background: none;"><i class="icon-check"></i></th>
                        <th class="span4" style="background: none;">Заголовок</th>
                        <th class="span2">Состояние</th>
                        <th>Доступ</th>
                        <th>Главная</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($menuItems) > 0): ?>
                        <?php foreach ($menuItems as $key => $menu): ?>
                            <?php $dash = NULL; ?>
                            <?php for ($i = 0; $i < $menu['dash'] - 1; $i++): ?>
                                <?php $dash.= '|----  '; ?>
                            <?php endfor; ?>
                            <tr id="menu_item_<?php echo $key?>">
                                <td>
                                    <input type="checkbox" name="menu_item_<?php echo $key; ?>" value="<?php echo $key; ?>" />
                                </td>
                                <td>
                                    <span class="menu-dash"><?php echo $dash; ?></span>
                                    <?php echo $menu['title']; ?>
                                </td>
                                <td class="status">
                                    <?php if ($menu['status'] == 0): ?>Не опубликовано<?php else: ?>Опубликовано<?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $menu['access']; ?>
                                </td>
                                <td class="for_index">
                                    <a href="" onclick="for_index(<?php echo $key; ?>);return false;">
                                        <?php if ($menu['for_index'] == 0): ?><i class="icon-star-empty"></i><?php else: ?><i class="icon-star"></i><?php endif; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($menuItems) > 0): ?>
                <div id="pager">
                    <form>
                        <a class="btn btn-small btn-info first" href="" onclick="return false;">
                            <i class="icon-fast-backward icon-white"></i>
                        </a>
                        <a class="btn btn-small btn-info prev" href="" onclick="return false;">
                            <i class="icon-chevron-left icon-white"></i>
                        </a>
                        <input type="text" class="pagedisplay span1 disabled" disabled=""/>
                        <a class="btn btn-small btn-info next" href="" onclick="return false;">
                            <i class="icon-chevron-right icon-white"></i>
                        </a>
                        <a class="btn btn-small btn-info last" href="" onclick="return false;">
                            <i class="icon-fast-forward icon-white"></i>
                        </a>
                        <select class="pagesize span1">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option  value="100">100</option>
                        </select>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>