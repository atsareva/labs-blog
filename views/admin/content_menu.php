<div id="load_for_ajax">
    <script type="text/javascript">
        // --------------------------------------------------------------------
        function dialog_menu()
        {
            $( "#dialog-menu" ).dialog({
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "Отменить": function() {
                        {
                            $( "#dialog-menu" ).find('label').empty();
                            $( this ).dialog( "close" );    
                        }
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        function public_menu(bool)
        {
            if($("input[name^=menu_]:checked").length == 0)
            {
                $( "#dialog-menu" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');    
                dialog_menu();
            }
            else
            {
                var data = $("input[name^=menu_]:checked");
                var mas = new Array;
                $.each(data, function(i, value){
                    mas[i] =value.defaultValue;
                });
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url:"/menu/public_menu",
                    data: 'data='+mas+'&bool='+bool,
                    success:function(data){
                        if (data)
                        {
                            $('#load_for_ajax').html(data);
                        }
                    }
                }); 
            }
        }
        // --------------------------------------------------------------------
        $(function() {
            $('#create').click(function(){
                $.ajax({
                    dataType: 'html',
                    url:"/menu/create",
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
                if ($("input[name^=menu_]:checked").length==1)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/menu/edit",
                        data: 'id_edit='+$("input[name^=menu_]:checked")[0].defaultValue,
                        success:function(data){
                            if (data)
                            {
                                $('#admin-content').html(data);
                            }
                        }
                    }); 
                }
                else if($("input[name^=menu_]:checked").length > 1)
                {
                    $( "#dialog-menu" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');    
                    dialog_menu();
                }
                else
                {
                    $( "#dialog-menu" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');    
                    dialog_menu();
                }
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#trash_menu').click(function(){
                if($("input[name^=menu_]:checked").length == 0)
                {
                    $( "#dialog-menu" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');    
                    dialog_menu();
                }
                else
                {
                    var data = $("input[name^=menu_]:checked");
                    var mas = new Array;
                    $.each(data, function(i, value){
                        mas[i] =value.defaultValue;
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/menu/trash_menu",
                        data: 'data='+mas,
                        success:function(data){
                            if (data)
                            {
                                $('#load_for_ajax').html(data);
                            }
                        }
                    }); 
                }
            });
        });
        // --------------------------------------------------------------------
<?php if (!empty($data)): ?>
        $(function(){
            $("#all_menu").tablesorter() 
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
    <div id="dialog-menu" style="display: none" title="Предупреждение!">
        <label></label>
    </div>
    <!--end dialog-->
    <div id="admin-content" class="row">
        <div class="span12" style="margin-left: 0px"> 
            <div class="install-steps">
                <div class="row">
                    <div class="span5">
                        Менеджер меню   
                    </div>
                    <div class="span5 offset1">
                        <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-pencil icon-white"></i> 
                        </a>
                        <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_menu(1);return false;">
                            <i class="icon-ok-circle icon-white"></i>
                        </a>
                        <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_menu(0);return false;">
                            <i class="icon-remove-circle icon-white"></i>
                        </a>
                        <span  style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a id="trash_menu" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-trash icon-white"></i>
                        </a>
                        <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin">
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
                                <?php if (!empty($data)): ?>
                                    <?php if (!isset($data[0]) || !is_array($data[0])): ?>
                                        <tr>
                                        <?php endif; ?>
                                        <?php foreach ($data as $index => $menu): ?>
                                            <?php if (is_array($menu)): ?>
                                            <tr>
                                                <?php foreach ($menu as $key => $value): ?>
                                                    <td>
                                                        <?php if ($key == 'id'): ?>
                                                            <?php $id = $value; ?>
                                                            <input type="checkbox" name="menu_<?php echo $value; ?>" value="<?php echo $value; ?>" />   
                                                        <?php elseif ($key == 'title'): ?>
                                                            <a href="?id=<?php echo $id; ?>"><?php echo $value; ?></a>
                                                        <?php else: ?>
                                                            <?php echo $value; ?>
                                                        <?php endif; ?>

                                                    </td>
                                                <?php endforeach; ?>
                                                <?php foreach ($count[$id] as $count_value): ?>
                                                    <td>
                                                        <?php echo $count_value; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php else: ?>
                                        <td>
                                            <?php if ($index == 'id'): ?>
                                                <?php $id = $menu; ?>
                                                <input type="checkbox" name="menu_<?php echo $menu; ?>" value="<?php echo $menu; ?>" /> 
                                            <?php elseif ($index == 'title'): ?>
                                                <a href="?id=<?php echo $id; ?>"><?php echo $menu; ?></a>
                                            <?php else: ?>
                                                <?php echo $menu; ?>
                                            <?php endif; ?>

                                        </td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (isset($data[0]) && is_array($data[0])): ?>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($count[$id] as $count_value): ?>
                                        <td>
                                            <?php echo $count_value; ?>
                                        </td>
                                    <?php endforeach; ?>
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