<div class="navbar">
    <div class="navbar-inner" style="width: 900px;">
        <div class="container">
            <a class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse"></a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="dropdown <?php if (isset($menu_profile) || isset($menu_index))
    echo 'active'; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-leaf icon-white"></i>
                            Сайт
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li <?php if (isset($menu_profile))
                            echo 'class="active"'; ?>>
                                <a href="/admin/profile">
                                    <i class="icon-user"></i>
                                    Мой профиль
                                </a>
                            </li>
                            <li <?php if (isset($menu_index))
                                    echo 'class="active"'; ?>>
                                <a href="/admin">
                                    <i class="icon-share-alt"></i>
                                    Панель управления
                                </a>
                            </li>
                            <li>
                                <a href="/auth/logout">
                                    <i class="icon-off"></i>
                                    Выйти
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown <?php if (isset($menu_menu))
                                    echo 'active'; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-align-justify icon-white"></i>
                            Меню
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li <?php if (isset($menu_menu))
                            echo 'class="active"'; ?>>
                                <a href="/admin/menu">
                                    <i class="icon-list-alt"></i>
                                    Менеджер меню
                                </a>
                            </li>
                            <?php if (!empty($admin_menu)): ?>
                                <hr/>
                                <?php if (isset($admin_menu[0]) && is_array($admin_menu[0])): ?>
                                    <?php foreach ($admin_menu as $menu): ?>
                                        <li>
                                            <a href="/admin/menu?id=<?php echo $menu['id']; ?>">
                                                <i class="icon-align-justify"></i>
                                                <?php echo $menu['title']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>
                                        <a href="/admin/menu?id=<?php echo $admin_menu['id']; ?>">
                                            <i class="icon-align-justify"></i>
                                            <?php echo $admin_menu['title']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="dropdown <?php if (isset($menu_content) || isset($menu_category))
                                echo 'active'; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-book icon-white"></i>
                            Материалы
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li <?php if (isset($menu_content))
                            echo 'class="active"'; ?>>
                                <a href="/admin/content">
                                    <i class="icon-file"></i>
                                    Менеджер материалов
                                </a>
                            </li>
                            <li <?php if (isset($menu_category))
                                    echo 'class="active"'; ?>>
                                <a href="/admin/category">
                                    <i class="icon-folder-close"></i>
                                    Менеджер категорий
                                </a>
                            </li>
                            <li <?php if (isset($menu_favourite))
                                    echo 'class="active"'; ?>>
                                <a href="/admin/favourite">
                                    <i class="icon-heart"></i>
                                    Избранное
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php if (isset($_SESSION['user']['access_id']) && ($_SESSION['user']['access_id'] == 3 || $_SESSION['user']['access_id'] == 5)): ?>
                        <li <?php if (isset($menu_users))
                        echo 'class="active"'; ?>>
                            <a href="/admin/users">
                                <i class="icon-user icon-white"></i>
                                Пользователи
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="nav pull-right" style="margin-right: 40px;">
                    <li>
                        <a href="/" target="_blank">Просмотр сайта</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
