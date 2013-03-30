<form method="post">
    <?php if (isset($response_edit) && $response_edit): ?>
        <?php $data_edit = $aux_handing->edit_page($response_edit);?>
        <input type="hidden" name="edit" value="<?php echo $response_edit; ?>"/>
    <?php endif; ?>

    Описание: <br/>
    <input type="text" name="description" value="<?php
    if (isset($data_edit['description']) && !empty($data_edit['description']))
    {
        echo $data_edit['description'];
    }
    ?>"/><br/>
    Ключевые слова: <br/>
    <input type="text" name="keywords" value="<?php
           if (isset($data_edit['keywords']) && !empty($data_edit['keywords']))
           {
               echo $data_edit['keywords'];
           }
    ?>"/><br/>
    Титул страницы: <br/>
    <input type="text" name="title" value="<?php
           if (isset($data_edit['title']) && !empty($data_edit['title']))
           {
               echo $data_edit['title'];
           }
    ?>"/><br/>
    Имя для меню: <br/>
    <input type="text" name="menu_name" value="<?php
           if (isset($data_edit['menu_name']) && !empty($data_edit['menu_name']))
           {
               echo $data_edit['menu_name'];
           }
    ?>"/><br/>
    Позиция в меню:<br/>
    <?php $menu = $aux_handing->menu_return(); ?>
    <select name="position">
        <?php if (isset($menu) && $menu): ?>
            <?php foreach ($menu as $position => $menu_name) : ?>
                <option value="<?php echo $position; ?>"><?php echo $position . ' - ' . $menu_name; ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php $end = (int) $position + 1; ?>
        <option value="<?php echo $end; ?>" selected="selected"><?php echo $end . ' - В конец списка'; ?></option>
    </select><br/>
    Видимость: <br/>
    <select name="visible"> 
        <option value="0">Не отображать</option>
        <option value="1">Отображать</option>
    </select><br/>
    URI страницы:<br/>
    <input type="text" name="uri" value="<?php
        if (isset($data_edit['uri']) && !empty($data_edit['uri']))
        {
            echo $data_edit['uri'];
        }
        ?>"/><br/>
    Текст страницы: <br/>
    <textarea id="editor" name="content"><?php
           if (isset($data_edit['content']) && !empty($data_edit['content']))
           {
               echo $data_edit['content'];
           }
        ?></textarea>
    <script type="text/javascript">
        CKEDITOR.replace( 'editor');
    </script>
    <br/>
    <input type="submit" name="create" value="Создать"/>
</form>