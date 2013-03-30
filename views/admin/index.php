<div id="admin-content" class="row">
    <div class="span12" style="margin-left: 0px"> 
        <div class="install-steps">
            <div class="row">
                <div class="span7">
                    <?php if (isset($user['user_name'])): ?>
                        <?php echo $user['user_name']; ?>, приветствуем Вас в панели управления.             
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <div class="well"  style="height:350px;"> 
                    <?php if (isset($user['photo'])): ?>
                        <div class="offset1">
                            <ul class="thumbnails">
                                <li class="span3">
                                    <a class="thumbnail" href="" onclick="return false;">
                                        <img alt="" src="/assets/resize/timthumb.php?src=<?php echo $user['photo']; ?>&h=200&w=200&zc=1" />                                  
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="span6">
                <div class="well" style="height:350px;">
                    <ul class="nav nav-list">
                        <li class="nav-header">
                            Сайт
                        </li>
                        <li>
                            <a href="/admin/profile">
                                <i class="icon-user"></i>
                                Мой профиль
                            </a>
                        </li>
                        <li class="active">
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
                        <li class="nav-header">
                            Меню
                        </li>
                        <li>
                            <a href="/admin/menu">
                                <i class="icon-list-alt"></i>
                                Менеджер меню
                            </a>
                        </li>
                        <li class="nav-header">
                            Материалы
                        </li>
                        <li>
                            <a href="/admin/content">
                                <i class="icon-file"></i>
                                Менеджер материалов
                            </a>
                        </li>
                        <li>
                            <a href="/admin/category">
                                <i class="icon-folder-close"></i>
                                Менеджер категорий
                            </a>
                        </li>
                        <li>
                            <a href="/admin/favourite">
                                <i class="icon-heart"></i>
                                Избранное
                            </a>
                        </li>
                        <?php if (isset($_SESSION['user']['access_id']) && ($_SESSION['user']['access_id'] == 3 || $_SESSION['user']['access_id'] == 5)): ?>
                        <li  class="nav-header">
                            <a href="/admin/users">
                                Пользователи
                            </a>
                        </li>
                    <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>

</div>