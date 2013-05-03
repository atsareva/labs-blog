<div class="navbar">
    <div class="navbar-inner">
        <div class="nav-collapse">
            <ul class="nav">
                <li class="dropdown<?php if (isset($menuProfile) || isset($menuIndex)): ?> active <?php endif; ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="" onclick="return false;">
                        <i class="icon-leaf icon-white"></i>Сайт<b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li<?php if (isset($menuProfile)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>auth/profile">
                                <i class="icon-user<?php if (isset($menuProfile)): ?> icon-white <?php endif; ?>"></i>Мой профиль
                            </a>
                        </li>
                        <li<?php if (isset($menuIndex)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>admin">
                                <i class="icon-share-alt<?php if (isset($menuIndex)): ?> icon-white <?php endif; ?>"></i>Панель управления
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo Core::getBaseUrl() ?>auth/logout">
                                <i class="icon-off"></i>Выйти
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown<?php if (isset($menuMenu)): ?> active <?php endif; ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="" onclick="return false;">
                        <i class="icon-align-justify icon-white"></i>Меню<b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li<?php if (isset($menuMenu)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>admin/menu">
                                <i class="icon-list-alt"></i>Менеджер меню
                            </a>
                        </li>
                        <?php $adminMenu = Core::getHelper('admin')->getMenuArray() ?>
                        <?php if (count($adminMenu) > 0): ?>
                            <hr/>
                            <?php foreach ($adminMenu as $menu): ?>
                                <li>
                                    <a href="<?php echo Core::getBaseUrl() ?>admin/menu/id/<?php echo $menu->id; ?>">
                                        <i class="icon-align-justify"></i>
                                        <?php echo $menu->title; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="dropdown<?php if (isset($menuContent) || isset($menuCategory) || isset($menuFavorite)): ?> active <?php endif; ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="" onclick="return false;">
                        <i class="icon-book icon-white"></i>Материалы<b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li<?php if (isset($menuContent)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>admin/content">
                                <i class="icon-file"></i>Менеджер материалов
                            </a>
                        </li>
                        <li<?php if (isset($menuCategory)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>admin/category">
                                <i class="icon-folder-close"></i>Менеджер категорий
                            </a>
                        </li>
                        <li<?php if (isset($menuFavorite)): ?> class="active" <?php endif; ?>>
                            <a href="<?php echo Core::getBaseUrl() ?>admin/favourite">
                                <i class="icon-heart"></i>Избранное
                            </a>
                        </li>
                    </ul>
                </li>
                <?php $user = Core::getHelper('user')->getUserInfo() ?>
                <?php if ($user && $user->getId() && $user->getAccessId() > 3): ?>
                    <li<?php if (isset($menuUsers)): ?> class="active" <?php endif; ?>>
                        <a href="<?php echo Core::getBaseUrl() ?>admin/users">
                            <i class="icon-user icon-white"></i>Пользователи
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="nav pull-right">
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>" target="_blank">Просмотр сайта</a>
                </li>
            </ul>
        </div>
    </div>
</div>
