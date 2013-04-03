<script type="text/javascript">
    function remove_folder()
    {
        $.ajax({
            type: "POST",
            url: "",
            data: 'remove='+1
        });
    }
</script>
<div class="span8 content-install"> 
    <div class="install-steps">
        <div class="row">
            <div class="span5">
                Завершение установки.       
            </div>
            <div class="span2">
                <a class="btn btn-small btn-info" href="/auth/login">
                    <i class="icon-cog icon-white"></i>
                    Панель управления
                </a>
            </div>
        </div>

    </div>
    <div class="install-check">
        <div class="row">
            <div class="span8">
                <div  style="margin: 10px;">
                    <h4>Поздравляем, вы установили SimpleCMS!</h4>
                </div>
                <hr/>
                <div  style="margin: 10px;">
                    Нажмите кнопку «Панель управления» для перехода к административной панели.
                </div>
                <center><div class="install-worning">
                        ВНИМАНИЕ: НЕ ЗАБУДЬТЕ ПОЛНОСТЬЮ
                        УДАЛИТЬ ДИРЕКТОРИЮ INSTALL.
                        Установка SimpleCMS! не будет завершена, пока Вы не удалите данную директорию.
                        <p>ПЕРЕД УДАЛЕНИЕМ УБЕДИТЕСЬ, ЧТО ФАЙЛ <span class="install-no">'config.php'</span> СУЩЕСТВУЕТ!</p>
                        <a id="remove_folder" class="btn btn-small btn-info" href="" onclick="remove_folder(); return false;">
                            <i class="icon-trash icon-white"></i>
                            Удалить директорию "install"
                        </a>
                        <hr/>
                    </div>
                </center>
                <center>
                    <div  style="margin: 10px;">
                        Возникла проблема создания конфигурационного файла. 
                        Необходимо вручную создать текстовый файл <span class="install-no">'config.php'</span> 
                        в папке <span class="install-no">'config'</span> и вставить в него текс, который приведен ниже. 
                    </div>
                </center>
                <pre class="prettyprint linenums">
&#60;?php
    class Config
    {

        protected $BASE_URL = '<?php echo $_SESSION['site_name'] ?>';

        public static $DEFULT_CONTROLLER = 'home';

        protected $DB_HOST = '<?php echo $_SESSION['db']['db_host'] ?>';
        protected $DB_USER = '<?php echo $_SESSION['db']['db_user'] ?>';
        protected $DB_PASS = '<?php echo $_SESSION['db']['db_pass'] ?>';
        protected $DB_NAME = '<?php echo $_SESSION['db']['db_name'] ?>';

    }

$config = new Config();
?&#62;
                </pre>
            </div>
        </div>
    </div>
</div>
