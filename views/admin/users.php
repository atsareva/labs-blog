<div id="load_for_ajax">
    <script type="text/javascript">
        // --------------------------------------------------------------------
        function dialog_user()
        {
            $( "#dialog-user" ).dialog({
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "Отменить": function() {
                        {
                            $( "#dialog-user" ).find('label').empty();
                            $( this ).dialog( "close" );    
                        }
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        function block_user(id)
        {
            $.ajax({
                type: "POST",
                dataType: 'html',
                url:"/users/block_user",
                data: 'id='+id,
                success:function(data){
                    if (data)
                    {
                        $('#load_for_ajax').html(data);
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        $(function() {
            $('#create').click(function(){
                $.ajax({
                    dataType: 'html',
                    url:"/users/create",
                    success:function(data){
                        if (data)
                        {
                            $('#admin-content').html(data);
                        }
                    }
                }); 
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#edit').click(function(){
                if ($("input[name^=user_]:checked").length==1)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/users/edit",
                        data: 'id_edit='+$("input[name^=user_]:checked")[0].defaultValue,
                        success:function(data){
                            if (data)
                            {
                                $('#admin-content').html(data);
                            }
                        }
                    }); 
                }
                else if($("input[name^=user_]:checked").length > 1)
                {
                    $( "#dialog-user" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного пользователя для редактирования!</p></div>');    
                    dialog_user();
                }
                else
                {
                    $( "#dialog-user" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали пользователя для редактирования.</p></div>');    
                    dialog_user();
                }
            });
        });
        // --------------------------------------------------------------------
<?php if (!empty($data)): ?>
        $(function(){
            $("#all_user").tablesorter() 
            .tablesorterPager({container: $("#pager")});
        });
<?php endif; ?>
    // --------------------------------------------------------------------

    </script>
    <style type="text/css">
        .pagedisplay, .pagesize{
            margin-top: 10px;
        }
        #pager .btn{
            padding: 2px;
        }
    </style>
    <!-- dialog users-->
    <div id="dialog-user" style="display: none" title="Предупреждение!">
        <label></label>
    </div>
    <!--end dialog-->
    <div id="admin-content" class="row">
        <div class="span12" style="margin-left: 0px"> 
            <div class="install-steps">
                <div class="row">
                    <div class="span5">
                        Пользователи   
                    </div>
                    <div class="span3 offset3">
                        <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-pencil icon-white"></i> 
                        </a>
                        <span  style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
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
                                <?php if (!empty($data)): ?>
                                    <?php if (!isset($data[0]) || !is_array($data[0])): ?>
                                        <tr>
                                        <?php endif; ?>
                                        <?php foreach ($data as $index => $user): ?>
                                            <?php if (!is_array($user)): ?>
                                                <td>
                                                    <?php if ($index == 'id'): ?>
                                                        <?php $id = $user; ?>
                                                        <?php if (isset($admin[$id]) && $_SESSION['user']['access_id'] != 5): ?>
                                                            <a rel="tooltip" title="Нет прав на редактирование администратора" href="" onclick="return false;">
                                                                <i class="icon-remove"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <input type="checkbox" name="user_<?php echo $user; ?>" value="<?php echo $user; ?>" /> 
                                                        <?php endif; ?>
                                                    <?php elseif ($index == 'block'): ?>
                                                        <?php if (isset($admin[$id]) && $_SESSION['user']['access_id'] != 5): ?>
                                                            <a rel="tooltip" title="Нет прав на редактирование администратора" href="" onclick="return false;">
                                                                <i class="icon-remove"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="" class="block_user" onclick="block_user(<?php echo $id; ?>);return false;">
                                                                <?php if ($user == 0): ?>
                                                                    <img src="/assets/img/un-block.png"/>
                                                                <?php else: ?>
                                                                    <img src="/assets/img/block.png"/>
                                                                <?php endif; ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php echo $user; ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php else: ?>
                                            <tr>
                                                <?php foreach ($user as $key => $value): ?>
                                                    <td>
                                                        <?php if ($key == 'id'): ?>
                                                            <?php $id = $value; ?>
                                                            <?php if (isset($admin[$id]) && $_SESSION['user']['access_id'] != 5): ?>
                                                                <a rel="tooltip" title="Нет прав на редактирование администратора" href="" onclick="return false;">
                                                                    <i class="icon-remove"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <input type="checkbox" name="user_<?php echo $value; ?>" value="<?php echo $value; ?>" /> 
                                                            <?php endif; ?>
                                                        <?php elseif ($key == 'block'): ?>
                                                            <?php if (isset($admin[$id]) && $_SESSION['user']['access_id'] != 5): ?>
                                                                <a rel="tooltip" title="Нет прав на редактирование администратора" href="" onclick="return false;">
                                                                    <i class="icon-remove"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="" class="block_user" onclick="block_user(<?php echo $id; ?>);return false;">
                                                                    <?php if ($value == 0): ?>
                                                                        <img src="/assets/img/un-block.png"/>
                                                                    <?php else: ?>
                                                                        <img src="/assets/img/block.png"/>
                                                                    <?php endif; ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php echo $value; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (!isset($data[0]) || !is_array($data[0])): ?>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (isset($data) && is_array($data)): ?>
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
        </div>
    </div>
</div>