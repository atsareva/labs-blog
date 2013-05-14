<script type="text/javascript" src="/assets/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving(){
        if ($("#form_material").valid())
        {
            $('#form_material').submit();
        }
        else{
            var err=$("#form_material").children().find('label.error')
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
    tinyMCE.init({
        mode:"textareas",
        theme:"advanced",
        language:"ru",
        elements : "elm_content",
        plugins : "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,flash",
        extended_valid_elements : 'script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder],div[*],p[*],object[width|height|classid|codebase|embed|param],param[name|value],embed[param|src|type|width|height|flashvars|wmode]',
        media_strict: false,
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,jbimages,|,cleanup,help,code,|insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,fullscreen,|,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_source_editor_height : "850",
        theme_advanced_source_editor_width : "500",
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        relative_urls : "false",
        remove_script_host : false,
        convert_urls : false
    });
    // --------------------------------------------------------------------
    $(function() {
        $( ".datepicker" ).datepicker({
            showOn: "button",
            buttonImage: "/assets/img/calendar.png",
            buttonImageOnly: true
        },$.datepicker.regional['ru']);
                        
    });
    // --------------------------------------------------------------------
    $(function() {
        $.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
        var dates = $( "#from, #to" ).datepicker({
            defaultDate: "+1w",
            showOn: "button",
            buttonImage: "/assets/img/calendar.png",
            buttonImageOnly: true,
            onSelect: function( selectedDate ) {
                var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" ),
                date = $.datepicker.parseDate(
                instance.settings.dateFormat ||
                    $.datepicker._defaults.dateFormat,
                selectedDate, instance.settings );
                dates.not( this ).datepicker( "option", option, date );
            }
        },$.datepicker.regional['ru']);
    });
    // --------------------------------------------------------------------
    $(function(){
        $('#choose').click(function(){
            $('#dialog-users .table').find('tbody').empty(); 
            $.ajax({
                dataType: 'json',
                url:"/ajax/load_users",
                success:function(data){
                    if (data)
                    {
                        var add_user;
                        $.each(data, function(i, value){
                            add_user="<td><input type='radio' class='"+value.user_name+"' name='user_id' value="+value.id+"></td>"
                            add_user+="<td><img src=/assets/resize/timthumb.php?src="+value.photo+"&h=50&w=50&zc=1></td>";
                            add_user+= "<td>"+value.user_name+" (<font color='#4AA2D9'>"+value.email+"</font>) </td>";  
                            $('#dialog-users .table').find('tbody')
                            .append($('<tr class="'+value.id+'">')
                            .append($(add_user)));    
                        });
                                       
                                                        
                        $( "#dialog-users" ).dialog({
                            height: 400,
                            width: 400,
                            modal: true,
                            buttons: {
                                "Выбрать": function() {
                                    if ($("input[name=user_id]:checked").length)
                                    {    
                                        $( "#dialog-users" ).find('label').empty();
                                        $('input[name=author_id]').val($("input[name=user_id]:checked")[0].defaultValue);
                                        $('input[name=author]').val($("input[name=user_id]:checked")[0].className);
                                        $( this ).dialog( "close" );
                                    }
                                    else
                                    {
                                        $( "#dialog-users" ).find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали автора материала.</p></div>');    
                                    }
                                },
                                "Отменить": function() {
                                    $( "#dialog-users" ).find('label').empty();
                                    $( this ).dialog( "close" );
                                }
                            }
                        }); 
                    }
                }
            }); 
        });
    });
    // -------------------------------------------------------------------- 
    $(function() {
        // validate signup form on keyup and submit
        $("#form_material").validate({
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
                Менеджер материалов: <?php
if (isset($edit) && $edit)
    echo "редактировать материал"; else
    echo "создать материал"
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
                <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin/content">
                    <i class="icon-home icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <form id="form_material" action="/content/create" method="post">
        <input type="hidden" name="save_exit" value="0"/>
        <input type="hidden" name="edit_material" value="<?php
            if (isset($edit) && $edit)
                echo 1; else
                echo 0;
?>"/>
        <input type="hidden" name="id_material" value="<?php
               if (isset($id_material))
                   echo $id_material; elseif (isset($result['id']))
                   echo $result['id']; else
                   echo '0';
?>"/>
        <div class="row">
            <div class="span12">
                <div class="well"> 

                    <!-- dialog users-->
                    <div id="dialog-users" style="display: none" title="Выбрать автора материала">
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

                    <h4>Создать материал</h4>
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
                                Избранное
                            </td>
                            <td>
                                <select name="favorite">
                                    <option value="0" <?php if (isset($result['favorite']) && $result['favorite'] == 0)
                                                echo 'selected="selected"'; ?>>Нет</option>
                                    <option value="1" <?php if (isset($result['favorite']) && $result['favorite'] == 1)
                                                echo 'selected="selected"'; ?>>Да</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Категория
                            </td>
                            <td>
                                <select name="category_id">
                                    <option value="0" selected="selected">-- Выбрать --</option>
                                    <?php if (!empty($category)): ?>
                                        <?php if (isset($category[0]) && is_array($category[0])): ?>
                                            <?php foreach ($category as $value): ?>
                                                <?php if (isset($result)): ?>
                                                    <?php if ($value['id'] == $result['category_id']): ?>
                                                        <option value="<?php echo $value['id'] ?>" selected="selected"><?php echo $value['title']; ?></option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['title']; ?></option>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['title']; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php if (isset($result)): ?>
                                                <?php if ($category['id'] == $result['category_id']): ?>
                                                    <option value="<?php echo $category['id'] ?>" selected="selected"><?php echo $category['title']; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['title']; ?></option>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <option value="<?php echo $category['id'] ?>"><?php echo $category['title']; ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Текст материала (сокращенный)
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table>
                        <tr class="span10">
                            <td>
                                <textarea id="intro_text" name="intro_text"><?php if (isset($result['intro_text']))
                                        echo $result['intro_text']; ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table>
                        <tr>
                            <td>
                                Текст материала (полный)
                            </td>
                        </tr>
                        <tr class="span10">
                            <td>
                                <textarea id="full_text" name="content"><?php if (isset($result['full_text']))
                                        echo $result['full_text']; ?></textarea>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="well"> 
                    <h4>Параметры публикации</h4>
                    <hr/>
                    <table>
                        <tr>
                            <td class="span3">
                                Автор
                                <input type="hidden" name="author_id" value="<?php if (isset($user))
                                        echo $user['id']; ?>"/>
                            </td>
                            <td>
                                <input class="disabled" disabled="" name="author" type="text" value="<?php if (isset($user))
                                           echo $user['user_name']; ?>"/>
                                <a style="margin-top: -8px;" rel="tooltip" title="Выбрать пользователя" id="choose" class="btn btn-small btn-info" href="" onclick="return false;">
                                    <i class="icon-user icon-white"></i> 
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Псевдоним автора
                            </td>
                            <td>
                                <input name="author_pseudo" type="text" value="<?php
                                       if (isset($result['author_pseudo']))
                                           echo $result['author_pseudo']; elseif (isset($_POST['author_pseudo']))
                                           echo $_POST['author_pseudo'];
                                    ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Дата создания
                            </td>
                            <td>
                                <input class="datepicker" name="created" type="text" value="<?php
                                       if (isset($result['created']))
                                           echo date('d.m.Y', $result['created']); elseif (isset($_POST['created']))
                                           echo $_POST['created'];
                                    ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Начало публикации
                            </td>
                            <td>
                                <input id="from" name="start_publication" type="text" value="<?php
                                       if (isset($result['start_publication']))
                                           echo date('d.m.Y', $result['start_publication']); elseif (isset($_POST['start_publication']))
                                           echo $_POST['start_publication'];
                                    ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Завершение публикации
                            </td>
                            <td>
                                <input id="to" name="end_publication" type="text" value="<?php
                                       if (isset($result['end_publication']) && $result['end_publication'] != 0)
                                           echo date('d.m.Y', $result['end_publication']); elseif (isset($_POST['end_publication']) && $_POST['end_publication'] != 0)
                                           echo $_POST['end_publication']; else
                                           echo '';
                                    ?>"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="well"> 
                    <h4>Метаданные</h4>
                    <hr/>
                    <table>
                        <tr>
                            <td class="span3">
                                Мета-тег Description  
                            </td>
                            <td>
                                <input name="description" type="text" value="<?php
                                       if (isset($result['description']))
                                           echo $result['description']; elseif (isset($_POST['description']))
                                           echo $_POST['description'];
                                    ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Мета-тег Keywords  
                            </td>
                            <td>
                                <input name="keywords" type="text" value="<?php
                                       if (isset($result['keywords']))
                                           echo $result['keywords']; elseif (isset($_POST['keywords']))
                                           echo $_POST['keywords'];
                                    ?>"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
