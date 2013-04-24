<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <div class="span3 offset9 login">
                <?php $user = Core::getHelper('user')->getUserInfo() ?>
                <?php if ($user && $user->getId()): ?>
                    Здравствуйте, <a href="<?php echo Core::getBaseUrl() ?>profile/index/<?php echo $user->getId() ?>"><?php echo $user->getUserName() ?></a> | <a href="<?php echo Core::getBaseUrl() ?>profile/logout">Выход</a>
                <?php else: ?>
                    <a href="<?php echo Core::getBaseUrl() ?>profile/signup">Регистрация</a> | <a href="<?php echo Core::getBaseUrl() ?>profile/login">Вход</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>