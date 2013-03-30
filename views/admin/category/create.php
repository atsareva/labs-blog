<script type="text/javascript" src="/assets/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving(){
        if ($("#form_category").valid())
        {
            $('#form_category').submit();
        }
        else{
            var err=$("#form_category").children().find('label.error')
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
        $("#form_category").validate({
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
                Менеджер категорий: <?php if (isset($edit) && $edit) echo "редактировать категорию"; else echo "создать категорию" ?>  
            </div>
            <div class="span3 offset2">
                <a rel="tooltip" title="Сохранить" id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-ok icon-white"></i>
                </a>
                <a rel="tooltip" title=" Сохранить и закрыть" id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                    <i class="icon-folder-close icon-white"></i> 
                </a>
                <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="/admin/category">
                    <i class="icon-home icon-white"></i>
                </a>
            </div>
        </div>
    </div>
    <form id="form_category" action="/category/create" method="post">
        <input type="hidden" name="save_exit" value="0"/>
        <input type="hidden" name="edit_category" value="<?php if (isset($edit) && $edit) echo 1; else echo 0; ?>"/>
        <input type="hidden" name="id_category" value="<?php if (isset($id_category)) echo $id_categoryl; elseif(isset($result['id'])) echo $result['id']; else echo '0';?>"/>
        <div class="row">
            <div class="span12">
                <div class="well"> 

                    <!-- dialog users-->
                    <div id="dialog-users" style="display: none" title="Выбрать автора категории">
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

                    <h4>Создать категорию</h4>
                    <hr/>

                    <table>
                        <tr>
                            <td class="span3">
                                Заголовок <span class="star">*</span> 
                            </td>
                            <td>
                                <input name="title" type="text" value="<?php if (isset($result['title'])) echo $result['title']; elseif (isset($_POST['title'])) echo $_POST['title'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Алиас <span class="star">*</span> 
                            </td>
                            <td>
                                <input name="alias" type="text" value="<?php if (isset($result['alias'])) echo $result['alias']; elseif (isset($_POST['alias'])) echo $_POST['alias'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Состояние
                            </td>
                            <td>
                                <select name="status">
                                    <option value="0" <?php if (isset($result['status']) && $result['status']==0) echo 'selected="selected"'; ?>>Не опубликовано</option>
                                    <option value="1" <?php if (isset($result['status']) && $result['status']==1) echo 'selected="selected"'; ?>>Опубликовано</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Текст категории
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <table>
                        <tr class="span10">
                            <td>
                                <textarea id="full_text" name="content"><?php if (isset($result['full_text'])) echo $result['full_text'];?></textarea>
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
                                <input type="hidden" name="author_id" value="<?php if (isset($user)) echo $user['id']; ?>"/>
                            </td>
                            <td>
                                <input class="disabled" disabled="" name="author" type="text" value="<?php if (isset($user)) echo $user['user_name']; ?>"/>
                                <a style="margin-top: -8px;" rel="tooltip" title="Выбрать пользователя" id="choose" class="btn btn-small btn-info" href="" onclick="return false;">
                                    <i class="icon-user icon-white"></i> 
                                </a>
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
                                <input name="description" type="text" value="<?php if (isset($result['description'])) echo $result['description']; elseif (isset($_POST['description'])) echo $_POST['description'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Мета-тег Keywords  
                            </td>
                            <td>
                                <input name="keywords" type="text" value="<?php if (isset($result['keywords'])) echo $result['keywords']; elseif (isset($_POST['keywords'])) echo $_POST['keywords'];?>"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>
