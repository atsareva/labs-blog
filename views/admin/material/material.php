<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_material()
    {
        $("#dialog-material").dialog({
            height: 200,
            width: 400,
            modal: true,
            buttons: {
                "Отменить": function() {
                    {
                        $("#dialog-material").find('label').empty();
                        $(this).dialog("close");
                    }
                }
            }
        });
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#edit').click(function() {
            if ($("input[name^=material_]:checked").length == 1)
                window.location = "<?php echo Core::getBaseUrl() ?>material/edit/" + $("input[name^=material_]:checked").val();
            else if ($("input[name^=material_]:checked").length > 1)
            {
                $("#dialog-material").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');
                dialog_material();
            }
            else
            {
                $("#dialog-material").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');
                dialog_material();
            }
        });
    });
    // --------------------------------------------------------------------
    function public_material(bool)
    {
        if ($("input[name^=material_]:checked").length == 0)
        {
            $("#dialog-material").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');
            dialog_material();
        }
        else
        {
            if (bool)
                var status = 'Опубликовано';
            else
                var status = 'Неопубликовано';
            var ids = '';
            $.each($("input[name^=material_]:checked"), function(i, element) {
                ids += $(element).val() + ',';
                $('#material_' + $(element).val() + ' td.status').html(status);
            })
            $.post("<?php echo Core::getBaseUrl() ?>material/publicMaterial", {'ids': ids, 'bool': bool});
        }
    }
    // --------------------------------------------------------------------
    function favorite(id)
    {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Core::getBaseUrl() ?>ajax/favoriteMaterial",
            data: 'id=' + id,
            success: function(response) {
                if (response.id)
                {
                    var html = '<a href="" onclick="favorite(' + response.id + ');return false;">';
                    if (response.favorite == 0)
                        html += '<img src="<?php echo Core::getBaseUrl() ?>assets/img/heart-black.png"/>';
                    else
                        html += '<img src="<?php echo Core::getBaseUrl() ?>assets/img/heart-red.png"/>';
                    html += '</a>';
                    $('#material_' + response.id + ' td.favorite').html(html);
                }
            }
        });

    }
// --------------------------------------------------------------------
    $(function() {
        $('#trash_material').click(function() {
            if ($("input[name^=material_]:checked").length == 0)
            {
                $("#dialog-material").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');
                dialog_menu();
            }
            else
            {
                var ids = '';
                $.each($("input[name^=material_]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#material_' + $(element).val()).hide();
                })
                $.post("<?php echo Core::getBaseUrl() ?>material/remove", {'ids': ids});
            }
        });
    });
    // --------------------------------------------------------------------
<?php if (count($materials) > 0): ?>
        $(function() {
            $("#all_material").tablesorter({widthFixed: true})
             .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
    // --------------------------------------------------------------------

</script>

<!-- dialog users-->
<div id="dialog-material" style="display: none" title="Предупреждение!">
    <label></label>
</div>
<!--end dialog-->

<div class="content-head">
    <div class="row">
        <div class="span5">
            <?php echo $head; ?>
        </div>
        <div class="span5 offset1">
            <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>material/create">
                <i class="icon-plus icon-white"></i>
            </a>
            <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-pencil icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_material(1);return false;">
                <i class="icon-ok-circle icon-white"></i>
            </a>
            <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_material(0);return false;">
                <i class="icon-remove-circle icon-white"></i>
            </a>
            <span  class="border"></span>
            <a id="trash_material" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
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
            <table id="all_material" class="tablesorter table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="background: none;"><i class="icon-check"></i></th>
                        <th class="span2">Заголовок</th>
                        <th class="span2">Состояние</th>
                        <th>Избранные</th>
                        <th>Категория</th>
                        <th>Автор</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($materials) > 0): ?>
                        <?php foreach ($materials as $material): ?>
                            <tr id="material_<?php echo $material->id ?>">
                                <td><input type="checkbox" name="material_<?php echo $material->id; ?>" value="<?php echo $material->id; ?>" /></td>
                                <td><?php echo $material->title; ?></td>
                                <td class="status"><?php if ($material->status == 1): ?>Опубликовано<?php else: ?>Неопубликовано<?php endif; ?></td>
                                <td class="favorite">
                                    <a href="" onclick="favorite(<?php echo $material->id ?>);
                return false;">
                                           <?php if ($material->favorite == 1): ?>
                                            <img src="<?php echo Core::getBaseUrl() ?>assets/img/heart-red.png"/>
                                        <?php else: ?>
                                            <img src="<?php echo Core::getBaseUrl() ?>assets/img/heart-black.png"/>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php if (!$material->category_title): ?>Без категории<?php else: ?><?php echo $material->category_title ?><?php endif; ?></td>
                                <td><?php echo $material->author ?></td>
                                <td><?php echo date('d.m.Y', $material->created) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($materials) > 0): ?>
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
