<html>
    <head>

        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->

        <!-- Custom Theme Style -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/custom.min.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/own.css" rel="stylesheet">
    </head>
    <body class="login">
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="/site/login" method="post" autocomplete="off" >
                        <h1>Авторизация</h1>
                        <div>
                            <input type="text" class="form-control" placeholder="Логин" name="LoginForm[username]" required="" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Пароль" name="LoginForm[password]" required="" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success submit">Войти</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">

                            <div >
                                <h1><img src="/images/CafeLogo.png" alt="" style="width: 100%"></h1>
                                <p>Copyright &copy; <?php echo date('Y'); ?> by <a target="_blank" href="http://mostbyte.uz">mostbyte.uz</a></p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </body>
</html>