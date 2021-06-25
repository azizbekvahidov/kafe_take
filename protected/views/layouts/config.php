<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" >
	<meta name="language" content="en"/><meta name="viewport" content="width=device-width, initial-scale=no">

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/print.css" media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/orders/ie.css" media="screen, projection">
	<![endif]-->

    <title>Настройка MostbyteCafe</title>

    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/loading-bar.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/custom.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/gentella/own.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/orders/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap3.js"></script>
    <!-- FastClick -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fastclick.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/loading-bar.min.js"></script>

</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <?php echo $content; ?>

    </div>
</div>




</body>
</html>
