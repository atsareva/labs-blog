<?php $menuItemsModel = Core::getModel('menu_items') ?>
<?php $menuItems      = $menuItemsModel->getMainNavItems() ?>

<?php foreach ($menuItemsModel->getMenus() as $menuId => $menuName): ?>
    <?php if ($menuName): ?>
        <h5><?php echo $menuName ?></h5>
    <?php endif; ?>
    <ul class="nav nav-list bs-docs-sidenav affix-top">
        <?php foreach ($menuItems[$menuId] as $key => $value): ?>
            <li>
                <?php if ($value['path']): ?>
                    <a href="<?php echo Core::getBaseUrl() . $value['path'] ?>">
                    <?php else: ?>
                        <a href="" onclick="return false;">
                        <?php endif; ?>
                        <?php for ($i = 0; $i < ($value['dash'] - 1); $i++): ?>
                            &emsp;
                        <?php endfor; ?>
                        <?php if ($value['dash'] == 1): ?>
                            <span class="parent">
                            <?php endif; ?>
                            <?php echo $value['title'] ?>
                            <?php if ($value['dash'] == 1): ?>
                            </span>
                        <?php endif; ?>
                    </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>