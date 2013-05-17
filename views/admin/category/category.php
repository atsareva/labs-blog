<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_category()
    {
        $("#dialog-category").dialog({
            height: 200,
            width: 400,
            modal: true,
            buttons: {
                "Отменить": function() {
                    {
                        $("#dialog-category").find('label').empty();
                        $(this).dialog("close");
                    }
                }
            }
        });
    }
    ;
    // --------------------------------------------------------------------
    function public_category(bool)
    {
        if ($("input[name^=category_]:checked").length == 0)
        {
            $("#dialog-category").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');
            dialog_category();
        }
        else
        {
            if (bool)
                var status = 'Опубликовано';
            else
                var status = 'Неопубликовано';
            var ids = '';
            $.each($("input[name^=category_]:checked"), function(i, element) {
                ids += $(element).val() + ',';
                $('#category_' + $(element).val() + ' td.status').html(status);
            })
            $.post("<?php echo Core::getBaseUrl() ?>category/publicCategory", {'ids': ids, 'bool': bool});
        }
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#edit').click(function() {
            if ($("input[name^=category_]:checked").length == 1)
                window.location = "<?php echo Core::getBaseUrl() ?>category/edit/" + $("input[name^=category_]:checked").val();
            else if ($("input[name^=category_]:checked").length > 1)
            {
                $("#dialog-category").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');
                dialog_category();
            }
            else
            {
                $("#dialog-category").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');
                dialog_category();
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        $('#trash_category').click(function() {
            if ($("input[name^=category_]:checked").length == 0)
            {
                $("#dialog-category").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');
                dialog_category();
            }
            else
            {
                var ids = '';
                $.each($("input[name^=category_]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#category_' + $(element).val()).hide();
                })
                $.post("<?php echo Core::getBaseUrl() ?>category/remove", {'ids': ids});
            }
        });
    });
    // --------------------------------------------------------------------
<?php if (count($categories) > 0): ?>
        $(function() {
            $("#all_category").tablesorter()
             .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
</script>

<!-- dialog users-->
<div id="dialog-category" style="display: none" title="Предупреждение!">
    <label></label>
</div>
<!--end dialog-->

<div class="content-head">
    <div class="row">
        <div class="span5">
            Менеджер категорий
        </div>
        <div class="span5 offset1">
            <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>category/create" onclick="">
                <i class="icon-plus icon-white"></i>
            </a>
            <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-pencil icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_category(1);
        return false;">
                <i class="icon-ok-circle icon-white"></i>
            </a>
            <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_category(0);
        return false;">
                <i class="icon-remove-circle icon-white"></i>
            </a>
            <span class="border"></span>
            <a id="trash_category" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
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
            <table id="all_category" class="tablesorter table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="background: none;"><i class="icon-check"></i></th>
                        <th>Заголовок</th>
                        <th>Состояние</th>
                        <th>Автор</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categories) > 0): ?>
                        <tr>
                            <?php foreach ($categories as $category): ?>
                            <tr id="category_<?php echo $category->id ?>">
                                <td><input type="checkbox" name="category_<?php echo $category->id; ?>" value="<?php echo $category->id; ?>" /></td>
                                <td><?php echo $category->title ?></td>
                                <td class="status"><?php if ($category->status == 1): ?>Опубликовано<?php else: ?>Неопубликовано<?php endif; ?></td>
                                <td><?php echo $category->author ?></td>
                                <td><?php echo date('d.m.Y', $category->created) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($categories) > 0): ?>
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