<script type="text/javascript">
    // --------------------------------------------------------------------
    function dialog_choose(elem)
    {

        $('#dialog-attach .table').find('tbody').empty(); 
        $.ajax({
            type: "POST",
            dataType: 'json',
            url:"/ajax/load_attach",
            data: 'attach='+elem,
            success:function(data){
                if (data)
                {
                    var add_user;
                    $.each(data, function(i, value){
                        add_user="<td><input type='radio' class='"+value.alias+"' name='elem_id' value="+value.id+"></td>"
                        add_user+= "<td><font color='#4AA2D9'>"+value.alias+"</font></td>";
                        add_user+= "<td>"+value.title+"</td>";  
                        $('#dialog-attach .table').find('tbody')
                        .append($('<tr class="'+value.id+'">')
                        .append($(add_user)));    
                    });
                                       
                                                        
                    $( "#dialog-attach" ).dialog({
                        height: 400,
                        width: 400,
                        modal: true,
                        buttons: {
                            "Выбрать": function() {
                                if ($("input[name=elem_id]:checked").length)
                                {    
                                    $( "#dialog-attach" ).find('label').empty();
                                    $('input[name=path]').val('page='+elem+'&id='+$("input[name=elem_id]:checked")[0].defaultValue);
                                    $( this ).dialog( "close" );
                                }
                                else
                                {
                                    $( "#dialog-attach" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали элемент.</p></div>');    
                                }
                            },
                            "Отменить": function() {
                                $( "#dialog-attach" ).find('label').empty();
                                $( this ).dialog( "close" );
                            }
                        }
                    }); 
                }
            }
        }); 
    }
    // --------------------------------------------------------------------
    function saving(){
        if ($("#form_menu_item").valid())
        {
            $('#form_menu_item').submit();
        }
        else{
            var err=$("#form_menu_item").children().find('label.error')
            $.each(err, function(i, olo){
                $('input[name='+olo.htmlFor+']').parent().addClass('control-group error');
            })
        }
    }
    // --------------------------------------------------------------------
    $(function() {  
        $('#save').click(function(){
            saving();
        })
        $('#save_exit').click(function(){
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
                menu_id:{
                    required: true
                },
                parent_id:{
                    required: true
                },
                for_index:{
                    required: true
                }
            },
            success: function(label) {
                label.removeClass('error').addClass("valid");
                $('input[name='+label[0].htmlFor+']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
    // --------------------------------------------------------------------
</script>

<style type="text/css">
    .attach .btn{
        padding: 2px;
        margin-right: 5px;
    }
</style>
<!-- dialog for attach material-->
<div id="dialog-attach" style="display: none" title="Выбрать элемент для связи">
    <label></label>
    <table class="table table-bordered table-striped">
        <colgroup>
            <col class="span1">
            <col class="span2">
            <col class="span4">
        </colgroup>
        <tbody>

        </tbody>
    </table>
</div>
<!--end dialog-->

<div class="span12" style="margin-left: 0px"> 
    <div class="install-steps">
        <div class="row">
            <div class="span6">
                Менеджер меню: <?php
if (isset($edit) && $edit)
    echo "редактировать пункт меню";
else
    echo "создать пункт меню";
?>  
            </div>
            <div class="span3 offset2">
                <a rel="tooltip" title="Сохранить" id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-ok icon-white"></i>
                </a>
                <a rel="tooltip" title=" Сохранить и закрыть" id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-folder-close icon-white"></i> 
                </a>
                <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin/menu?id=<?php echo $_GET['id']; ?>">
                    <i class="icon-home icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <form id="form_menu_item" action="/menu/create_item" method="post">
        <input type="hidden" name="save_exit" value="0"/>
        <input type="hidden" name="edit_menu_item" value="<?php
                if (isset($edit) && $edit)
                    echo 1; else
                    echo 0;
?>"/>
        <input type="hidden" name="id_menu_item" value="<?php
               if (isset($id_menu_item))
                   echo $id_menu_item;
               elseif (isset($result['id']))
                   echo $result['id'];
               else
                   echo '0';
?>"/>
        <div class="row">
            <div class="span12">
                <div class="well"> 

                    <h4>Создать пункт меню</h4>
                    <hr/>

                    <table>
                        <tr>
                            <td class="span3">
                                Заголовок <span class="star">*</span> 
                            </td>
                            <td>
                                <input name="title" type="text" value="<?php
               if (isset($result['title']))
                   echo $result['title']; elseif (isset($_POST['title']))
                   echo $_POST['title'];
?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="span3">
                                Алиас <span class="star">*</span> 
                            </td>
                            <td>
                                <input name="alias" type="text" value="<?php
                                       if (isset($result['alias']))
                                           echo $result['alias']; elseif (isset($_POST['alias']))
                                           echo $_POST['alias'];
?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Меню <span class="star">*</span>
                            </td>
                            <td>
                                <select name="menu_id">
                                    <option value="0">-- Выбрать --</option>
                                    <?php if (isset($main_menu[0]) && is_array($main_menu[0])): ?>
                                        <?php foreach ($main_menu as $value): ?>
                                            <?php if (isset($result) && $value['id'] == $result['menu_id']): ?>
                                                <option selected="selected" value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php if (isset($main_menu) && isset($result['menu_id']) && $main_menu['id'] == $result['menu_id']): ?>
                                            <option selected="selected" value="<?php echo $main_menu['id']; ?>"><?php echo $main_menu['title']; ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $main_menu['id']; ?>"><?php echo $main_menu['title']; ?></option>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Родительский элемент <span class="star">*</span>
                            </td>
                            <td>
                                <select name="parent_id">
                                    <option value="0">-- Корневой элемент --</option>
                                    <?php if (isset($child_items[0]) && is_array($child_items[0])): ?>
                                        <?php foreach ($child_items as $value): ?>
                                            <?php $dash = NULL; ?>
                                            <?php for ($i = 0; $i < $value['dash'] - 1; $i++): ?>
                                                <?php $dash.= '|----  '; ?>
                                            <?php endfor; ?>
                                            <?php if (isset($result) && $value['id'] != $result['id']): ?>
                                                <?php if ($value['id'] == $result['parent_id']): ?>
                                                    <option selected="selected" value="<?php echo $value['id']; ?>"><span class="menu-dash"><?php echo $dash; ?></span><?php echo $value['title']; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $value['id']; ?>"><span class="menu-dash"><?php echo $dash; ?></span><?php echo $value['title']; ?></option>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <option value="<?php echo $value['id']; ?>"><span class="menu-dash"><?php echo $dash; ?></span><?php echo $value['title']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Главная страница <span class="star">*</span> 
                            </td>
                            <td>
                                <span style="margin-right: 50px;"><input type="radio" <?php if (isset($result['for_index']) && $result['for_index'] == 0)
                                        echo 'checked="checked"'; ?> name="for_index" value="0"/>Нет</span>
                                <input type="radio" <?php if (isset($result['for_index']) && $result['for_index'] == 1)
                                                                             echo 'checked="checked"'; ?> name="for_index" value="1"/>Да
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Состояние
                            </td>
                            <td>
                                <select name="status">
                                    <option value="0" <?php if (isset($result['status']) && $result['status'] == 0)
                                           echo 'selected="selected"'; ?>>Не опубликовано</option>
                                    <option value="1" <?php if (isset($result['status']) && $result['status'] == 1)
                                                echo 'selected="selected"'; ?>>Опубликовано</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Позиция
                            </td>
                            <td>
                                <?php if (!isset($result) || empty($result)): ?>
                                    Настройка позиции будет доступна после сохранения элемента
                                <?php else: ?>
                                    <select name="position">
                                        <?php if (isset($order_of) && !empty($order_of)): ?>
                                            <?php if (!isset($order_of[0]) || !is_array($order_of[0])): ?>
                                                <option selected="selected" value="<?php echo $value['id']; ?>">Первый элемент</option>
                                            <?php else: ?>
                                                <?php foreach ($order_of as $value): ?>
                                                    <?php if ($value['id'] == $result['id']): ?>
                                                        <option selected="selected" value="<?php echo $value['id']; ?>"><?php echo $value['order_of'] . ' - ' . $value['title']; ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['order_of'] . ' - ' . $value['title']; ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </select>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Доступ
                            </td>
                            <td>
                                <select name="access_id">
                                    <?php if (isset($access)): ?>
                                        <?php foreach ($access as $value): ?>
                                            <?php if (isset($result['access_id']) && $result['access_id'] == $value['id']): ?>
                                                <option selected="selected" value="<?php echo $value['id']; ?>"><?php echo $value['description']; ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['description']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="attach">
                            <td>
                                Прикрепить
                            </td>
                            <td>
                                <input name="path" readonly value="<?php
                                    if (isset($result['path']))
                                        echo $result['path']; elseif (isset($_POST['path']))
                                        echo $_POST['path'];
                                    ?>" />
                                <br/>
                                <br/>
                                <a rel="tooltip" title="Выбрать материал" class="btn btn-small btn-info" href="" onclick="dialog_choose('material'); return false;">
                                    <i class="icon-ok icon-white"></i>
                                </a>Материал
                                <br/>
                                <br/>
                                <a rel="tooltip" title="Выбрать категорию" class="btn btn-small btn-info" href=""  onclick="dialog_choose('category'); return false;">
                                    <i class="icon-ok icon-white"></i>
                                </a>Категория
                        </tr>
                    </table>
                    <br/>
                </div>
            </div>
        </div>
    </form>
</div>
