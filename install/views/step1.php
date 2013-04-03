<div class="span8 content-install"> 
    <div class="install-steps">
        <div class="row">
            <div class="span4">
                Начальная проверка.       
            </div>
            <div class="span3">
                <a id="reload" class="btn btn-small btn-info" href="" onclick="reload_content(); return false;">
                    <i class="icon-repeat icon-white"></i>
                    Повторить попытку
                </a>
                <a class="btn btn-small btn-info" href="install.php?configuration">
                    Далее
                    <i class="icon-arrow-right icon-white"></i>
                </a>
            </div>
        </div>

    </div>
    <div class="install-check">
        <div class="row">
            <div class="span8">
                <div  style="margin: 10px;">
                    &nbsp;&nbsp;&nbsp;Если любая из этих установок не поддерживается (выделена как <span class="install-no">Нет</span>), 
                    то настройки вашей системы не соответствуют минимально-необходимым требованиям. 
                    Пожалуйста, измените настройки вашей системы и повторите проверку. 
                    Иначе, это может привести к сбою при установке и некорректной работе системы. 
                </div>
                <table class="table">
                    <tr>
                        <td class="span4">
                            Версия PHP >= 5.2.4 
                        </td>
                        <td>
                            <?php if ($php_version == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            Поддержка XML
                        </td>
                        <td>
                            <?php if ($xml_support == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Поддержка MySQL 
                        </td>
                        <td>
                            <?php if ($mysql_support == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            Поддержка JSON
                        </td>
                        <td>
                            <?php if ($json_support == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            Поддержка INI Parser 
                        </td>
                        <td>
                            <?php if ($ini_parser == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            Доступен на запись config.php
                        </td>
                        <td>
                            <?php if ($config_file == TRUE): ?>
                                <span class="install-yes">Да</span>
                            <?php else: ?>
                                <span class="install-no">Нет</span>
                                <p>&nbsp;&nbsp;&nbsp;Вы можете продолжить установку, 
                                    после чего в конце будет показана конфигурация. 
                                    Вам будет необходимо выполнить ещё один дополнительный шаг - загрузить код вручную.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <hr/>
            </div>
        </div>
    </div>
</div>
