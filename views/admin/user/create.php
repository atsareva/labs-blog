<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving(){
        if ($("#form_user").valid())
        {
            $('#form_user').submit();
        }
        else{
            var err=$("#form_user").children().find('label.error')
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
        $("#form_user").validate({
            rules: {
                login: {
                    required: true
                },
                pass: {
                    required: true
                },
                confirm_pass: {
                    required: true,
                    equalTo: $('#pass')
                },
                email: {
                    required: true,
                    email: true
                },
                status_id: {
                    required: true
                },
                access_id: {
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
                Пользователи: <?php
if (isset($edit) && $edit)
    echo "редактировать пользователя";
else
    echo "создать пользователя";
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
                <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin/users">
                    <i class="icon-home icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <form id="form_user" action="/users/create" method="post">
        <input type="hidden" name="save_exit" value="0"/>
        <input type="hidden" name="edit_user" value="<?php
                if (isset($edit) && $edit)
                    echo 1; else
                    echo 0;
?>"/>
        <input type="hidden" name="id_user" value="<?php
               if (isset($id_user))
                   echo $id_user;
               elseif (isset($result['id']))
                   echo $result['id'];
               else
                   echo '0';
?>"/>
        <div class="row">
            <div class="span12">
                <div class="well"> 

                    <h4>Создать пользователя</h4>
                    <hr/>
                    <table>
                        <tr>
                            <td class="span4"> 
                                Логин <span class="star">*</span> 
                            </td>
                            <td>
                                <input type="text" name="login" value="<?php if (isset($result['user_name']))
                   echo $result['user_name']; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Пароль <span class="star">*</span> 
                            </td>
                            <td>
                                <input id="pass" type="password" name="pass" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Повтор пароля <span class="star">*</span> 
                            </td>
                            <td>
                                <input type="password" name="confirm_pass" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                E-mail <span class="star">*</span> 
                            </td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">@</span>
                                    <input type="text" name="email" style="width: 182px;" value="<?php if (isset($result['email']))
                                           echo $result['email']; ?>"/>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Статус <span class="star">*</span> 
                            </td>
                            <td>
                                <select name="status_id">
                                    <option value="0">-- Выбрать --</option>
                                    <?php if (isset($status)): ?>
                                        <?php foreach ($status as $value): ?>
                                            <?php if (isset($result) && $result['status_id'] == $value['id']): ?>
                                                <option selected="selected" value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                            <?php else: ?>       
                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                            <?php endif; ?>  
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Доступ <span class="star">*</span> 
                            </td>
                            <td>
                                <select name="access_id">
                                    <option value="0">-- Выбрать --</option>
                                    <?php if (isset($access)): ?>
                                        <?php foreach ($access as $value): ?>
                                            <?php if (isset($result) && $result['access_id'] == $value['id']): ?>
                                                <option selected="selected" value="<?php echo $value['id'] ?>"><?php echo $value['description'] ?></option>
                                            <?php else: ?>       
                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['description'] ?></option>
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
