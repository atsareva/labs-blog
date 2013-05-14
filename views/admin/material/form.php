<?php
echo $this->getJs(array(
    Core::getBaseUrl() . "assets/tinymce/jscripts/tiny_mce/tiny_mce.js",
    Core::getBaseUrl() . "assets/tinymce/jscripts/tiny_mce/jquery.tinymce.js"
));

echo $this->getCss(array(
    Core::getBaseUrl() . "assets/tinymce/jscripts/tiny_mce/themes/advanced/skins/default/ui.css",
    Core::getBaseUrl() . "assets/tinymce/jscripts/tiny_mce/plugins/inlinepopups/skins/clearlooks2/window.css"
));
?>
<script type="text/javascript">
    // --------------------------------------------------------------------
    function saving() {
        if ($("#form_material").valid())
        {
            $('#form_material').submit();
        }
        else {
            var err = $("#form_material").children().find('label.error')
            $.each(err, function(i, element) {
                $('input[name=' + element.htmlFor + ']').parent().addClass('control-group error');
            })
        }
    }
    // --------------------------------------------------------------------
    $(function() {
        $('#save').click(function() {
            saving();
        })
        $('#save_exit').click(function() {
            $('input[name=save_exit]').val(1);
            saving();
        })
    });
    // -------------------------------------------------------------------- 
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        language: "ru",
        elements: "elm_content",
        plugins: "jbimages,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        extended_valid_elements: 'script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder],div[*],p[*],object[width|height|classid|codebase|embed|param],param[name|value],embed[param|src|type|width|height|flashvars|wmode]',
        media_strict: false,
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,jbimages,|,cleanup,help,code,|insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,fullscreen,|,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_source_editor_height: "850",
        theme_advanced_source_editor_width: "500",
        theme_advanced_resizing: true,
        theme_advanced_resize_horizontal: false,
        relative_urls: "false",
        remove_script_host: false,
        convert_urls: false
    });
    // --------------------------------------------------------------------
    $(function() {
        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: "<?php echo Core::getBaseUrl()?>assets/img/calendar.png",
            buttonImageOnly: true
        }, $.datepicker.regional['ru']);

    });
    // --------------------------------------------------------------------
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional[ "ru" ]);
        var dates = $("#from, #to").datepicker({
            defaultDate: "+1w",
            showOn: "button",
            buttonImage: "<?php echo Core::getBaseUrl()?>assets/img/calendar.png",
            buttonImageOnly: true,
            onSelect: function(selectedDate) {
                var option = this.id == "from" ? "minDate" : "maxDate",
                 instance = $(this).data("datepicker"),
                 date = $.datepicker.parseDate(
                 instance.settings.dateFormat ||
                 $.datepicker._defaults.dateFormat,
                 selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        }, $.datepicker.regional['ru']);
    });
    // --------------------------------------------------------------------
    $(function() {
        $('#choose').click(function() {
            $('#dialog-users .table').find('tbody').empty();
            $.ajax({
                dataType: 'json',
                url: "<?php echo Core::getBaseUrl()?>ajax/loadUsers",
                success: function(data) {
                    if (data)
                    {
                        var add_user;
                        $.each(data, function(i, value) {
                            add_user = "<td><input type='radio' class='" + value.user_name + "' name='user_id' value=" + value.id + "></td>"
                            add_user += "<td><img src=/assets/resize/timthumb.php?src=" + value.photo + "&h=50&w=50&zc=1></td>";
                            add_user += "<td>" + value.user_name + " (<font color='#4AA2D9'>" + value.email + "</font>) </td>";
                            $('#dialog-users .table').find('tbody')
                             .append($('<tr class="' + value.id + '">')
                             .append($(add_user)));
                        });


                        $("#dialog-users").dialog({
                            height: 400,
                            width: 400,
                            modal: true,
                            buttons: {
                                "Выбрать": function() {
                                    if ($("input[name=user_id]:checked").length)
                                    {
                                        $("#dialog-users").find('label').empty();
                                        $('input[name=author_id]').val($("input[name=user_id]:checked")[0].defaultValue);
                                        $('input[name=author]').val($("input[name=user_id]:checked")[0].className);
                                        $(this).dialog("close");
                                    }
                                    else
                                    {
                                        $("#dialog-users").find('label').append('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>Вы не выбрали автора материала.</p></div>');
                                    }
                                },
                                "Отменить": function() {
                                    $("#dialog-users").find('label').empty();
                                    $(this).dialog("close");
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
            messages: {
                title: {
                    required: "Это обязательное поле!"
                },
                alias: {
                    required: "Это обязательное поле!"
                },
            },
            success: function(label) {
                label.removeClass('error').addClass("valid");
                $('input[name=' + label[0].htmlFor + ']').parent().removeClass('control-group error').addClass('control-group success');
            }
        });
    });
    // -------------------------------------------------------------------- 
</script>
<div class="content-head">
    <div class="row">
        <div class="span6">
            Менеджер материалов: <?php echo $head ?>
        </div>
        <div class="span3 offset2">
            <a rel="tooltip" title="Сохранить" id="save" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-ok icon-white"></i>
            </a>
            <a rel="tooltip" title=" Сохранить и закрыть" id="save_exit" class="btn btn-small btn-info" href="" onclick="return false;">
                <i class="icon-folder-close icon-white"></i>
            </a>
            <span class="border"></span>
            <a rel="tooltip" title="Отмена" class="btn btn-small btn-info" href="<?php echo Core::getBaseUrl() ?>admin/content">
                <i class="icon-home icon-white"></i>
            </a>
        </div>
    </div>
</div>
<form id="form_material" action="<?php echo $materialUrl ?>" method="post">
    <input type="hidden" name="save_exit" value="0"/>
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

                <h4><?php echo $head ?></h4>
                <hr/>

                <table>
                    <tr>
                        <td class="span3">Заголовок <span class="star">*</span></td>
                        <td>
                            <input name="title" type="text" value="<?php if (isset($material)): ?><?php echo $material->getTitle() ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Алиас <span class="star">*</span></td>
                        <td>
                            <input name="alias" type="text" value="<?php if (isset($material)): ?><?php echo $material->getAlias() ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Состояние</td>
                        <td>
                            <select name="status">
                                <option value="0" <?php if (isset($material) && $material->getStatus() == 0): ?>selected="selected"<?php endif ?>>Не опубликовано</option>
                                <option value="1" <?php if (isset($material) && $material->getStatus() == 1): ?>selected="selected"<?php endif ?>>Опубликовано</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Избранное</td>
                        <td>
                            <select name="favorite">
                                <option value="0" <?php if (isset($material) && $material->getFavorite() == 0): ?>selected="selected"<?php endif ?>>Нет</option>
                                <option value="1" <?php if (isset($material) && $material->getFavorite() == 1): ?>selected="selected"<?php endif ?>>Да</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Категория</td>
                        <td>
                            <select name="category_id">
                                <option value="0" selected="selected">-- Выбрать --</option>
                                <?php if (count($categories) > 0): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option <?php if (isset($material) && $category->id == $material->getCategoryId()): ?> selected="selected" <?php endif; ?> value='<?php echo $category->id; ?>'><?php echo $category->title; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Текст материала (сокращенный)</td>
                    </tr>
                </table>
                <br/>
                <table>
                    <tr class="span10">
                        <td>
                            <textarea id="intro_text" name="intro_text"><?php if (isset($material) && $material->getIntroText()): ?><?php echo $material->getIntroText() ?><?php endif; ?></textarea>
                        </td>
                    </tr>
                </table>
                <br/>
                <table>
                    <tr>
                        <td>Текст материала (полный)</td>
                    </tr>
                    <tr class="span10">
                        <td>
                            <textarea id="full_text" name="content"><?php if (isset($material) && $material->getFullText()): ?><?php echo $material->getFullText() ?><?php endif; ?></textarea>
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
                            <input type="hidden" name="author_id" value="<?php if (isset($author)): ?><?php echo $author->getId() ?><?php endif; ?>"/>
                        </td>
                        <td>
                            <input class="disabled" disabled="" name="author" type="text" value="<?php if (isset($author)): ?><?php echo $author->getUserName() ?><?php endif; ?>"/>
                            <a style="margin-top: -8px;" rel="tooltip" title="Выбрать пользователя" id="choose" class="btn btn-small btn-info" href="" onclick="return false;">
                                <i class="icon-user icon-white"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Псевдоним автора</td>
                        <td>
                            <input name="author_pseudo" type="text" value="<?php if (isset($author) && $author->getAuthorPseudo()): ?><?php echo $author->getAuthorPseudo() ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Дата создания</td>
                        <td>
                            <input class="datepicker" name="created" type="text" value="<?php if (isset($material)): ?><?php echo date('m/d/Y', $material->getCreated()) ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Начало публикации</td>
                        <td>
                            <input id="from" name="start_publication" type="text" value="<?php if (isset($material) && $material->getStartPublication()): ?><?php echo date('m/d/Y', $material->getStartPublication()) ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Завершение публикации</td>
                        <td>
                            <input id="to" name="end_publication" type="text" value="<?php if (isset($material) && $material->getEndPublication()): ?><?php echo date('m/d/Y', $material->getEndPublication()) ?><?php endif; ?>"/>
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
                        <td class="span3">Мета-тег Description</td>
                        <td>
                            <input name="description" type="text" value="<?php if (isset($material) && $material->getDescription()): ?><?php echo $material->getDescription() ?><?php endif; ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Мета-тег Keywords</td>
                        <td>
                            <input name="keywords" type="text" value="<?php if (isset($material) && $material->getKeywords()): ?><?php echo $material->getKeywords() ?><?php endif; ?>"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
