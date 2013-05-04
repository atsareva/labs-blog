<div id="load_for_ajax">
    <script type="text/javascript">
        // --------------------------------------------------------------------
        function dialog_menu_item()
        {
            $( "#dialog-menu_item" ).dialog({
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "Отменить": function() {
                        {
                            $("#dialog-menu_item" ).find('label').empty();
                            $( this ).dialog( "close" );    
                        }
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        function public_menu_item(bool)
        {
            if($("input[name^=menu_item_]:checked").length == 0)
            {
                $( "#dialog-menu_item" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');    
                dialog_menu_item();
            }
            else
            {
                var data = $("input[name^=menu_item_]:checked");
                var mas = new Array;
                $.each(data, function(i, value){
                    mas[i] =value.defaultValue;
                });
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url:"/menu/public_menu_item",
                    data: 'data='+mas+'&bool='+bool+'&parent_id=<?php echo $_GET['id']; ?>',
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
                    type: "GET",
                    dataType: 'html',
                    url:"/menu/create_item",
                    data:'id=<?php echo $_GET['id']; ?>',
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
                if ($("input[name^=menu_item_]:checked").length==1)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/menu/edit_item_menu",
                        data: 'id_edit='+$("input[name^=menu_item_]:checked")[0].defaultValue+'&parent_id=<?php echo $_GET['id']; ?>',
                        success:function(data){
                            if (data)
                            {
                                $('#admin-content').html(data);
                            }
                        }
                    }); 
                }
                else if($("input[name^=menu_item_]:checked").length > 1)
                {
                    $( "#dialog-menu_item" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');    
                    dialog_menu_item();
                }
                else
                {
                    $( "#dialog-menu_item" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');    
                    dialog_menu_item();
                }
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#trash_menu_item').click(function(){
                if($("input[name^=menu_item_]:checked").length == 0)
                {
                    $( "#dialog-menu_item" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');    
                    dialog_menu_item();
                }
                else
                {
                    var data = $("input[name^=menu_item_]:checked");
                    var mas = new Array;
                    $.each(data, function(i, value){
                        mas[i] =value.defaultValue;
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/menu/trash_menu_item",
                        data: 'data='+mas+'&parent_id=<?php echo $_GET['id']; ?>',
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
        function for_index(id)
        {
            $.ajax({
                type: "POST",
                dataType: 'html',
                url:"/menu/for_index",
                data: 'id='+id+'&parent_id=<?php echo $_GET['id']; ?>',
                success:function(data){
                    if (data)
                    {
                        $('#load_for_ajax').html(data);
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
<?php if (!empty($data)): ?>
        $(function(){
            $("#all_menu_item").tablesorter() 
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
    <div id="dialog-menu_item" style="display: none" title="Предупреждение!">
        <label></label>
    </div>
    <!--end dialog-->
    <div id="admin-content" class="row">
        <div class="span12" style="margin-left: 0px"> 
            <div class="install-steps">
                <div class="row">
                    <div class="span5">
                        Менеджер пунктов меню   
                    </div>
                    <div class="span5 offset1">
                        <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-pencil icon-white"></i> 
                        </a>
                        <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_menu_item(1);return false;">
                            <i class="icon-ok-circle icon-white"></i>
                        </a>
                        <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_menu_item(0);return false;">
                            <i class="icon-remove-circle icon-white"></i>
                        </a>
                        <span  style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a id="trash_menu_item" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
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
                                <?php if (!empty($data)): ?>
                                    <?php foreach ($data as $key => $menu): ?>
                                        <?php $dash = NULL; ?>
                                        <?php for ($i = 0; $i < $menu['dash']-1; $i++): ?>
                                            <?php $dash.= '|----  '; ?>
                                        <?php endfor; ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="menu_item_<?php echo $menu['id']; ?>" value="<?php echo $menu['id']; ?>" />   
                                            </td>
                                            <td>
                                                <span class="menu-dash"><?php echo $dash; ?></span>
                                                <?php echo $menu['title']; ?>
                                            </td>
                                            <td>
                                                <?php if ($menu['status'] == 0): ?>
                                                    Не опубликовано
                                                <?php else: ?>
                                                    Опубликовано
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo $menu['access_id']; ?>
                                            </td>
                                            <td>
                                                <a href="" class="for_index" onclick="for_index(<?php echo $menu['id']; ?>);return false;">
                                                    <?php if ($menu['for_index'] == 0): ?>
                                                        <i class="icon-star-empty"></i>
                                                    <?php else: ?>
                                                        <i class="icon-star"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (isset($data) && is_array($data) && !empty($data)): ?>
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