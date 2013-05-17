<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_menu()
    {
        $("#dialog-menu").dialog({
            height: 200,
            width: 400,
            modal: true,
            buttons: {
                "Отменить": function() {
                    {
                        $("#dialog-menu").find('label').empty();
                        $(this).dialog("close");
                    }
                }
            }
        });
    }
    ;
    // --------------------------------------------------------------------
    function public_menu(bool)
    {
        if ($("input[name^=menu_]:checked").length == 0)
        {
            $("#dialog-menu").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');
            dialog_menu();
        }
        else
        {
            if (bool)
                var status = 'Опубликовано';
            else
                var status = 'Неопубликовано';
            var ids = '';
                $.each($("input[name^=menu_]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#menu_' + $(element).val() + ' td.status').html(status);
                })
            $.post("<?php echo Core::getBaseUrl() ?>menu/publicMenu", {'ids': ids, 'bool': bool});
        }
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#edit').click(function() {
            if ($("input[name^=menu_]:checked").length == 1)
                window.location = "<?php echo Core::getBaseUrl() ?>menu/edit/" + $("input[name^=menu_]:checked").val();
            else if ($("input[name^=menu_]:checked").length > 1)
            {
                $("#dialog-menu").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');
                dialog_menu();
            }
            else
            {
                $("#dialog-menu").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');
                dialog_menu();
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        $('#trash_menu').click(function() {
            if ($("input[name^=menu_]:checked").length == 0)
            {
                $("#dialog-menu").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');
                dialog_menu();
            }
            else
            {
                var ids = '';
                $.each($("input[name^=menu_]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#menu_' + $(element).val()).hide();
                })
                $.post("<?php echo Core::getBaseUrl() ?>menu/remove", {'ids': ids});
            }
        });
    });
    // --------------------------------------------------------------------
<?php if (count($menu) > 0): ?>
        $(function() {
            $("#all_menu").tablesorter()
             .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
    // --------------------------------------------------------------------

</script>

<!-- dialog users-->
<div id="dialog-menu" style="display: none" title="Предупреждение!">
    <label></label>
</div>
<!-- dialog users-->

<div class="content-head">
    <div class="row">
        <div class="span5">
            Менеджер меню
        </div>
        <div class="span5 offset1">
            <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>menu/create">
                <i class="icon-plus icon-white"></i>
            </a>
            <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-pencil icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_menu(1);return false;">
                <i class="icon-ok-circle icon-white"></i>
            </a>
            <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_menu(0);return false;">
                <i class="icon-remove-circle icon-white"></i>
            </a>
            <span class="border"></span>
            <a id="trash_menu" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-trash icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="span12">
        <div class="well">
            <table id="all_menu" class="tablesorter table table-bordered table-striped">
                <thead>
                    <tr>
                        <th rowspan="2" style="background: none;"><i class="icon-check"></i></th>
                        <th rowspan="2" class="span2">Заголовок</th>
                        <th rowspan="2" class="span2">Состояние</th>
                        <th colspan="3" style="border-bottom: 1px solid #DDD; background: none;">Количество пунктов меню</th>
                    </tr>
                    <tr class="header">
                        <th style="border-left: 1px solid #DDD;">Опубликованных</th>
                        <th>Неопубликованных</th>
                        <th>В корзине</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($menu) > 0): ?>
                    <?php foreach ($menu as $item): ?>
                        <tr id="menu_<?php echo $item->id?>">
                            <td><input type="checkbox" name="menu_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" /></td>
                            <td><a title="<?php echo $item->title ?>" href="<?php echo Core::getBaseUrl() ?>admin/menu/<?php echo $item->id ?>"><?php echo $item->title; ?></a></td>
                            <td class="status"><?php if ($item->status == 1): ?>Опубликовано<?php else: ?>Неопубликовано<?php endif; ?></td>
                            <td><?php echo $item->public; ?></td>
                            <td><?php echo $item->not_public; ?></td>
                            <td><?php echo $item->material_trash; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($menu) > 0): ?>
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
