<script type="text/javascript" src="/assets/js/material.js"></script>

<div id="load_for_ajax">
    <script type="text/javascript">
<?php if (!empty($data)): ?>
        $(function(){
            $("#all_material").tablesorter({widthFixed: true}) 
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
    <div id="dialog-material" style="display: none" title="Предупреждение!">
        <label></label>
    </div>
    <!--end dialog-->
    <div id="admin-content" class="row">
        <div class="span12" style="margin-left: 0px"> 
            <div class="install-steps">
                <div class="row">
                    <div class="span5">
                        <?php if (isset($menu_favourite) && $menu_favourite == TRUE): ?>
                            Избранные материалы
                        <?php else: ?>
                            Менеджер материалов
                        <?php endif; ?>
                    </div>
                    <div class="span5 offset1">
                        <a rel="tooltip" title="Создать" id="create" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-plus icon-white"></i>
                        </a>
                        <a rel="tooltip" title=" Редактировать" id="edit" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-pencil icon-white"></i> 
                        </a>
                        <span style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a rel="tooltip" title="Опубликовать" class="btn btn-small btn-info" href="" onclick="public_material(1);return false;">
                            <i class="icon-ok-circle icon-white"></i>
                        </a>
                        <a rel="tooltip" title="Снять с публикации" class="btn btn-small btn-info" href="" onclick="public_material(0);return false;">
                            <i class="icon-remove-circle icon-white"></i>
                        </a>
                        <a id="favorite_material" rel="tooltip" title="Избранное" class="btn btn-small btn-info" href="" onclick="return false;">
                            <i class="icon-heart icon-white"></i>
                        </a>
                        <span  style="border-right: 2px solid #C6C9C9; margin: 0 10px 0 5px;"></span>
                        <a id="trash_material" rel="tooltip" title="Переместить в корзину" class="btn btn-small btn-info" href="" onclick="return false;">
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
                        <table id="all_material" class="tablesorter table table-bordered table-striped"> 
                            <thead> 
                                <tr> 
                                    <th style="background: none;"><i class="icon-check"></i></th>
                                    <th class="span2">Заголовок</th> 
                                    <th class="span2">Состояние</th> 
                                    <th>Избранные</th> 
                                    <th>Категория</th> 
                                    <th>Автор</th> 
                                    <th>Дата</th> 
                                </tr> 
                            </thead> 
                            <tbody> 
                                <?php if (!empty($data)): ?>
                                    <?php if (!isset($data[0]) || !is_array($data[0])): ?>
                                        <tr>
                                        <?php endif; ?>
                                        <?php foreach ($data as $index => $material): ?>
                                            <?php if (is_array($material)): ?>
                                            <tr>
                                                <?php foreach ($material as $key => $value): ?>
                                                    <td>
                                                        <?php if ($key == 'favorite'): ?>
                                                            <?php if ($value == 0): ?>
                                                                <a href="" class="favorite" onclick="return false;"><img src="/assets/img/heart-black.png"/></a>
                                                            <?php else: ?>
                                                                <a href="" class="favorite" onclick="return false;"><img src="/assets/img/heart-red.png"/></a>
                                                            <?php endif; ?>
                                                        <?php elseif ($key == 'id'): ?>
                                                            <input type="checkbox" name="material_<?php echo $value; ?>" value="<?php echo $value; ?>" />   
                                                        <?php else: ?>
                                                            <?php echo $value; ?>
                                                        <?php endif; ?>

                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php else: ?>
                                        <td>
                                            <?php if ($index == 'favorite'): ?>
                                                <?php if ($material == 0): ?>
                                                    <a href="" class="favorite" onclick="return false;"><img src="/assets/img/heart-black.png"/></a>
                                                <?php else: ?>
                                                    <a href="" class="favorite" onclick="return false;"><img src="/assets/img/heart-red.png"/></a>
                                                <?php endif; ?>
                                            <?php elseif ($index == 'id'): ?>
                                                <input type="checkbox" name="material_<?php echo $material; ?>" value="<?php echo $material; ?>" />   
                                            <?php else: ?>
                                                <?php echo $material; ?>
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