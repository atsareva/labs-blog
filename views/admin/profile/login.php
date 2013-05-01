<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <?php echo $this->getChild($this->_head); ?>
    <body class="<?php echo $this->getBaseClass() ?>">

        <?php echo $this->getChild($this->_navBar); ?>

        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="mini-layout login">
                        <center><h4>Вход в панель управления</h4></center>
                        <hr/>
                        <div class="login-form">
                            <p>Введите существующие логин и пароль доступа к Панели управления.</p>
                            <?php if (($error)): ?>
                                <div class="alert alert-error">
                                    <strong>Ошибка! </strong><?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <form action="<?php echo Core::getBaseUrl() ?>auth/login" method="post">
                                <div class="fieldset">
                                    <div>
                                        <p>Логин&nbsp;<span class="star">*</span></p>
                                        <input type="text" name="login" value=""/>
                                    </div>
                                    <div>
                                        <p>Пароль&nbsp;<span class="star">*</span></p>
                                        <input type="password" name="pass" value=""/>
                                    </div>

                                </div>
                                <div class='button-set'>
                                    <div>
                                        <a href="<?php echo Core::getBaseUrl() ?>home">Перейти на главную страницу</a>
                                    </div>
                                    <div>
                                        <button class="btn btn-small btn-info" title='Войти'>
                                            <i class="icon-user icon-white"></i>
                                            Войти
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>