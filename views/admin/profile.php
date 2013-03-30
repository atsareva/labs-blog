<link rel="stylesheet" href="/assets/uploadify/uploadify.css" type="text/css"/>
<script type="text/javascript" src="/assets/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/assets/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<style type="text/css">
    #photoQueue .uploadifyQueueItem{
        margin: 5px 0 0 -129px;
    }
</style>
<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving(){
        if ($("#profile").valid())
        {
            $('#profile').submit();
        }
        else{
            var err=$("#profile").children().find('label.error')
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
        $("#photo").uploadify({
            'uploader' : '/assets/uploadify/uploadify.swf', 
            'script' : '/assets/uploadify/uploadify.php', 
            'cancelImg': '/assets/uploadify/cancel.png',
            'folder': '/assets/upload',
            'multi': false,
            'auto' : true,
            'removeCompleted' : false,
            'scriptAccess' 	: 'always',
            'checkScript': '/assets/uploadify/check.php',
            'fileDesc'   : 'jpg;png;gif;jpeg',
            'fileExt'   : '*.jpg;*.png;*.gif;*.jpeg',
            'onError'  : function (event,ID,fileObj,errorObj) {                                                                                   
                alert('<p>'+errorObj.type + ' Error: ' + errorObj.info+'</p>');
            },
            'onSelect': function(){
                //$(".save").prop("disabled", true); 
            },
            'onComplete': function(event, ID, fileObj, response, data) {
                if (response==1)
                {
                    $("#photo").uploadifyCancel(ID);
                    alert('Превишен максимальный размер загрузки файла!');           
                }
                else
                {
                    $('#attachment').val(response); 
                    $('.thumbnail img').attr('src', "/assets/resize/timthumb.php?src="+response+"&h=200&w=200&zc=1")
           
                }                },
            'onCancel':function(event, ID, fileObj, data, remove, clearFast){
                $('input[name=attachments]').val('');
            }
        });
    });
    // -------------------------------------------------------------------- 
    $(function() {
        // validate signup form on keyup and submit
        $("#profile").validate({
            rules: {
                login: {
                    required: true
                },
                email: {
                    required: true,
                    email : true
                },
                confirm_pass: {
                    equalTo: $('#pass')
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

<div id="admin-content" class="row">
    <div class="span12" style="margin-left: 0px"> 
        <div class="install-steps">
            <div class="row">
                <div class="span4">
                    Мой профиль.       
                </div>
                <div class="span5 offset2">
                    <a id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                        <i class="icon-check icon-white"></i>
                        Сохранить
                    </a>
                    <a id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                        <i class="icon-folder-close icon-white"></i>
                        Сохранить и закрыть
                    </a>
                    <a class="btn btn-small btn-info" href="/admin">
                        <i class="icon-home icon-white"></i>
                        Отмена
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <div class="well" style="height:400px;"> 
                    <h4>Параметры моего профиля</h4>
                    <hr/>
                    <?php if (isset($result['error']))
                        echo $result['error']; ?>
                    <form id="profile" action="" method="post">
                        <input type="hidden" name="save_exit" value="0"/>
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
                                <td class="span4"> 
                                    Ваше ФИО
                                </td>
                                <td>
                                    <input type="text" name="full_name" value="<?php if (isset($result['full_name']))
                                               echo $result['full_name']; ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Пароль 
                                </td>
                                <td>
                                    <input id="pass" type="password" name="pass" value=""/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Повтор пароля
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
                                    Факультет 
                                </td>
                                <td>
                                    <select name="faculty_id">
                                        <option value="0">-- Выбрать --</option>
                                        <?php if (isset($faculties)): ?>
                                            <?php foreach ($faculties as $value): ?>
                                                <?php if (isset($result) && $result['faculty_id'] == $value['id']): ?>
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
                                    Кафедра 
                                </td>
                                <td>
                                    <select name="department_id">
                                        <option value="0">-- Выбрать --</option>
                                        <?php if (isset($departments)): ?>
                                            <?php foreach ($departments as $value): ?>
                                                <?php if (isset($result) && $result['department_id'] == $value['id']): ?>
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
                                    Статус
                                </td>
                                <td>
                                    <select name="status_id">
                                        <option value="0">-- Выбрать --</option>
                                        <?php if (isset($user_status)): ?>
                                            <?php foreach ($user_status as $value): ?>
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
                                    Дата регистрации 
                                </td>
                                <td>
                                    <?php if (isset($result['register_date']))
                                        echo date('d/m/Y H:i:s', $result['register_date']); ?> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Дата последнего входа
                                </td>
                                <td>
                                    <?php if (isset($result['last_login']))
                                        echo date('d/m/Y H:i:s', $result['last_login']); ?> 
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" id="attachment" name="attachment" value="<?php if (isset($result['photo']) && !empty($result['photo']))
                                        echo $result['photo']; ?>"/>
                    </form>
                </div>
            </div>
            <div class="span6">
                <div class="well" style="height:400px;">
                    <h4>Загрузка аватара</h4>
                    <hr/>
                    <div class="span5 offset1">
                        <ul class="thumbnails">
                            <li class="span3">
                                <a class="thumbnail" href="" onclick="return false;">
                                    <?php if (isset($result['photo']) && !empty($result['photo'])): ?>
                                        <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $result['photo']; ?>&h=200&w=200&zc=1" />
                                    <?php else: ?>
                                        <img alt="" src="/assets/img/default.png" />
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="span2" style="margin: -10px 0 0 149px;">
                        <input id="photo" class="input-file" type="file"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>