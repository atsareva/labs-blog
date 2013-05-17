<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_user()
    {
        $("#dialog-user").dialog({
            height: 200,
            width: 400,
            modal: true,
            buttons: {
                "Отменить": function() {
                    {
                        $("#dialog-user").find('label').empty();
                        $(this).dialog("close");
                    }
                }
            }
        });
    }
    ;
    // --------------------------------------------------------------------
    function block_user(id)
    {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo Core::getBaseUrl() ?>ajax/blockUser",
            data: 'id=' + id,
            success: function(response) {
                if (response.id)
                {
                    var html = '<a href="" onclick="block_user(' + response.id + ');return false;">';
                    if (response.block == 0)
                        html += '<img alt="" title="Разблокировать" src="<?php echo Core::getBaseUrl() ?>assets/img/un-block.png"/>';
                    else
                        html += '<img alt="" title="Заблокировать" src="<?php echo Core::getBaseUrl() ?>assets/img/block.png"/>';
                    html += '</a>';
                    $('#user_' + response.id + ' td.block-user').html(html);
                }
            }
        });

    }
    // --------------------------------------------------------------------
    $(function() {
        $('#edit').click(function() {
            if ($("input[name^=user_]:checked").length == 1)
            {
                window.location = '<?php echo Core::getBaseUrl() ?>users/edit/' + $("input[name^=user_]:checked").val();
            }
            else if ($("input[name^=user_]:checked").length > 1)
            {
                $("#dialog-user").find('label').html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного пользователя для редактирования!</p></div>');
                dialog_user();
            }
            else
            {
                $("#dialog-user").find('label').html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали пользователя для редактирования.</p></div>');
                dialog_user();
            }
        });
    });
    // --------------------------------------------------------------------
    $(function() {
        $('#remove').click(function() {
            if ($("input[name^=user_]:checked").length >= 1)
            {
                var ids = '';
                $.each($("input[name^=user_]:checked"), function(i, element) {
                    ids += $(element).val() + ',';
                    $('#user_' + $(element).val()).hide();
                })
                $.post("<?php echo Core::getBaseUrl() ?>users/remove", {'ids': ids});
            }
        })
    })
    // --------------------------------------------------------------------
<?php if (count($users) > 0): ?>
        $(function() {
            $("#all_user").tablesorter()
             .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
    // --------------------------------------------------------------------

</script>
<!-- dialog users-->
<div id="dialog-user" style="display: none" title="Предупреждение!">
    <label></label>
</div>
<!--end dialog-->

<div class="content-head">
    <div class="row">
        <div class="span5">
            Пользователи
        </div>
        <div class="span3 offset3">
            <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>users/create">
                <i class="icon-plus icon-white"></i>
            </a>
            <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-pencil icon-white"></i>
            </a>
            <a rel="tooltip" title=" Удалить" id="remove" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-remove icon-white"></i>
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
            <table id="all_user" class="tablesorter table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="span1" style="background: none;"><i class="icon-check"></i></th>
                        <th class="span2">Пользователь</th>
                        <th class="span3">Имя</th>
                        <th class="span2">Статус</th>
                        <th style="background: none;">Блок</th>
                        <th class="span2">Email</th>
                        <th class="span2">Доступ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr id="user_<?php echo $user->id ?>">
                                <td><input type="checkbox" name="user_<?php echo $user->id; ?>" value="<?php echo $user->id; ?>" /></td>
                                <td><?php echo $user->user_name; ?></td>
                                <td><?php echo $user->full_name; ?></td>
                                <td><?php echo $user->status_id; ?></td>
                                <td class="block-user">
                                    <a href="" onclick="block_user(<?php echo $user->id; ?>);return false;">
                                           <?php if ($user->block == 0): ?>
                                            <img alt="" title="Разблокировать" src="<?php echo Core::getBaseUrl() ?>assets/img/un-block.png"/>
                                        <?php else: ?>
                                            <img alt="" title="Заблокировать" src="<?php echo Core::getBaseUrl() ?>assets/img/block.png"/>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo $user->access; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (count($users) > 0): ?>
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
