<div class="span3" style="margin-left: 30px;">
    <div class="row">
        <div style="margin-top: 10px;">
            <?php if (isset($_SESSION['user']['access_id']) && $_SESSION['user']['access_id'] > 2): ?>
                <a href="/admin">Перейти в панель управления</a>
            <?php endif; ?>
        </div>       
        <?php if (!empty($items_menu)): ?>
            <?php foreach ($items_menu as $key => $items): ?>

                <?php if (!empty($menu_name) && isset($menu_name[$key])): ?>
                    <h3>
                        <?php echo $menu_name[$key]; ?>
                    </h3>
                <?php endif; ?>
                <ul class="nav nav-tabs nav-stacked">
                    <?php foreach ($items as $value): ?>
                        <?php if ($value['status'] == 1): ?>
                            <li>
                                <?php if ($value['dash'] == 1): ?>
                                    <?php if (!$value['path']): ?>
                                        <a href="" onclick="return false;"><span class="parent"><?php echo $value['title']; ?></span></a>
                                    <?php else: ?>
                                        <a href="<?php echo $value['path']; ?>"><span class="parent"><?php echo $value['title']; ?></span></a>
                                    <?php endif; ?>                               
                                <?php else: ?> 
                                    <?php if (!$value['path']): ?>
                                        <a href="" onclick="return false;">
                                        <?php else: ?>               
                                            <a href="<?php echo $value['path']; ?>">
                                            <?php endif; ?> 
                                            <?php for ($i = 0; $i < $value['dash']; $i++): ?>
                                                &emsp;
                                            <?php endfor; ?>
                                            <?php echo $value['title']; ?>
                                        </a>
                                    <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
