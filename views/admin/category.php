<script type="text/javascript" src="/assets/js/tablesorter.js"></script>
<script type="text/javascript" src="/assets/js/tablesorter.pager.js"></script>

<div id="load_for_ajax">
    <script type="text/javascript">
        // --------------------------------------------------------------------
        function dialog_category()
        {
            $( "#dialog-category" ).dialog({
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "Отменить": function() {
                        {
                            $( "#dialog-category" ).find('label').empty();
                            $( this ).dialog( "close" );    
                        }
                    }
                }
            }); 
        };
        // --------------------------------------------------------------------
        function public_category(bool)
        {
            if($("input[name^=category_]:checked").length == 0)
            {
                $( "#dialog-category" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для публикации.</p></div>');    
                dialog_category();
            }
            else
            {
                var data = $("input[name^=category_]:checked");
                var mas = new Array;
                $.each(data, function(i, value){
                    mas[i] =value.defaultValue;
                });
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url:"/category/public_category",
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
                    url:"/category/create",
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
                if ($("input[name^=category_]:checked").length==1)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/category/edit",
                        data: 'id_edit='+$("input[name^=category_]:checked")[0].defaultValue,
                        success:function(data){
                            if (data)
                            {
                                $('#admin-content').html(data);
                            }
                        }
                    }); 
                }
                else if($("input[name^=category_]:checked").length > 1)
                {
                    $( "#dialog-category" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы выбрали больше одного материала для редактирования!</p></div>');    
                    dialog_category();
                }
                else
                {
                    $( "#dialog-category" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал для редактирования.</p></div>');    
                    dialog_category();
                }
            });
        });
        // --------------------------------------------------------------------
        $(function(){
            $('#trash_category').click(function(){
                if($("input[name^=category_]:checked").length == 0)
                {
                    $( "#dialog-category" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали материал.</p></div>');    
                    dialog_category();
                }
                else
                {
                    var data = $("input[name^=category_]:checked");
                    var mas = new Array;
                    $.each(data, function(i, value){
                        mas[i] =value.defaultValue;
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'html',
                        url:"/category/trash_category",
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
                $("#all_category").tablesorter() 
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
    <div id="dialog-edit-category" style="display: none" title="Предупреждение!">
        <label></label>
    </div>
    <!--end dialog-->
    <div id="admin-content" class="row">
        <div class="span12" style="margin-left: 0px"> 
            <div class="install-steps">
                <div class="row">
                    <div class="span5">
                        Менеджер категорий   
                    </div>
                    <div class="span5 offset1">
                        <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-pencil icon-white"></i> 
                        </a>
                        <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_category(1);return false;">
                            <i class="icon-ok-circle icon-white"></i>
                        </a>
                        <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_category(0);return false;">
                            <i class="icon-remove-circle icon-white"></i>
                        </a>
                        <span  style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a id="trash_category" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
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
                                <?php if (!empty($data)): ?>
                                    <?php if (!isset($data[0]) || !is_array($data[0])): ?>
                                        <tr>
                                        <?php endif; ?>
                                        <?php foreach ($data as $index => $category): ?>
                                            <?php if (is_array($category)): ?>
                                            <tr>
                                                <?php foreach ($category as $key => $value): ?>
                                                    <td>
                                                        <?php if ($key == 'id'): ?>
                                                            <input type="checkbox" name="category_<?php echo $value; ?>" value="<?php echo $value; ?>" />   
                                                        <?php else: ?>
                                                            <?php echo $value; ?>
                                                        <?php endif; ?>

                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php else: ?>
                                        <td>
                                            <?php if ($index == 'id'): ?>
                                                <input type="checkbox" name="category_<?php echo $category; ?>" value="<?php echo $category; ?>" />   
                                            <?php else: ?>
                                                <?php echo $category; ?>
                                            <?php endif; ?>

                                        </td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (isset($data[0]) && is_array($data[0])): ?>
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