<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving(){
        if ($("#form_menu").valid())
        {
            $('#form_menu').submit();
        }
        else{
            var err=$("#form_menu").children().find('label.error')
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
        $("#form_menu").validate({
            rules: {
                title: {
                    required: true
                },
                alias: {
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
<div class="span12" style="margin-left: 0px"> 
    <div class="install-steps">
        <div class="row">
            <div class="span6">
                Менеджер меню: <?php
if (isset($edit) && $edit)
    echo "редактировать меню";
else
    echo "создать меню";
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
                <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin/menu">
                    <i class="icon-home icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <form id="form_menu" action="/menu/create" method="post">
        <input type="hidden" name="save_exit" value="0"/>
        <input type="hidden" name="edit_menu" value="<?php
                if (isset($edit) && $edit)
                    echo 1; else
                    echo 0;
?>"/>
        <input type="hidden" name="id_menu" value="<?php
               if (isset($id_menu))
                   echo $id_menu; 
               elseif (isset($result['id']))
                   echo $result['id']; 
               else
                   echo '0';
?>"/>
        <div class="row">
            <div class="span12">
                <div class="well"> 

                    <h4>Создать меню</h4>
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
                            <td>
                                Показывать заголовок <span class="star">*</span> 
                            </td>
                            <td>
                                <span style="margin-right: 50px;">
                                    <input type="radio" <?php if (isset($result['show_title']) && $result['show_title'] == 0)
                                           echo 'checked="checked"'; ?> name="show_title" value="0"/>Нет
                                </span>
                                <input type="radio" <?php if (isset($result['show_title']) && $result['show_title'] == 1)
                                                                             echo 'checked="checked"'; ?> name="show_title" value="1"/>Да
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
                                <select name="position">
                                    <option value="0">--Выбрать--</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Доступ
                            </td>
                            <td>
                                <select name="access">
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

                    </table>
                    <br/>
                </div>
            </div>
        </div>
    </form>
</div>
