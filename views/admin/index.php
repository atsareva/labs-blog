<?php $user = Core::getHelper('user')->getUserInfo() ?>
<div class="content-head">
    <div class="row">
        <div class="span7">
            <?php echo $user->getUserName(); ?>, приветствуем Вас в панели управления.
        </div>
    </div>
</div>
<div class="row">
    <div class="span6">
        <div class="well">
            <?php if ($user->getPhoto()): ?>
                <div class="offset1">
                    <ul class="thumbnails">
                        <li class="span3">
                            <a class="thumbnail" href="" onclick="return false;">
                                <img alt="" src="<?php echo Core::getBaseUrl() ?>assets/resize/timthumb.php?src=<?php echo $user->getPhoto(); ?>&h=200&w=200&zc=1" />
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <ul class="nav nav-list">
                <li class="nav-header">
                    Сайт
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>admin/profile">
                        <i class="icon-user"></i>Мой профиль
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo Core::getBaseUrl() ?>admin">
                        <i class="icon-share-alt"></i>Панель управления
                    </a>
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>auth/logout">
                        <i class="icon-off"></i>Выйти
                    </a>
                </li>
                <li class="nav-header">
                    Меню
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>admin/menu">
                        <i class="icon-list-alt"></i>Менеджер меню
                    </a>
                </li>
                <li class="nav-header">
                    Материалы
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>admin/content">
                        <i class="icon-file"></i>Менеджер материалов
                    </a>
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>admin/category">
                        <i class="icon-folder-close"></i>Менеджер категорий
                    </a>
                </li>
                <li>
                    <a href="<?php echo Core::getBaseUrl() ?>admin/favourite">
                        <i class="icon-heart"></i>Избранное
                    </a>
                </li>
                <?php if ($user->getAccessId() >= 3): ?>
                    <li  class="nav-header">
                        <a href="<?php echo Core::getBaseUrl() ?>admin/users">
                            Пользователи
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>