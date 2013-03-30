<script type="text/javascript">
    $(function(){
        $('#login_user').click(function(){
            $('#login_form').submit();
        });
    })
</script>
<div class="row">
    <div class="span6 offset4">
        <div class="mini-layout login">
            <center><h3>Вход в панель управления</h3></center>
            <hr/>
            <div class="row">
                <p style="color: #C1C1C1; margin: 0px 0px 17px 33px;">
                    Введите существующие логин и пароль доступа к Панели управления.
                </p>
                <form id="login_form" action="" method="post">
                    <div class="span4" style="margin: 7px 0 0 43px;">
                        <div class="row">
                            <div class="span1" style="margin-top: 5px;">
                                Логин&nbsp;<span class="star">*</span>
                            </div>
                            <div class="span3">
                                <input type="text" name="login" value=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span1" style="margin-top: 5px;">
                                Пароль&nbsp;<span class="star">*</span>
                            </div>
                            <div class="span3">
                                <input type="password" name="pass" value=""/>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="span2 offset2" style="margin-left: 241px;">
                                &nbsp;&nbsp;&nbsp;<a id="login_user" class="btn btn-small btn-info" href="" onclick="return false;">
                                    Войти
                                    <i class="icon-lock icon-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br/><br/>
            <p style="margin: -20px 0px 0px 143px; color: #5ABCD9">
                <a href="/home">Перейти на главную страницу</a>
            </p>
        </div>
    </div>
</div>