<?php
require Yii::app()->basePath . '/../vendor/autoload.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
class SiteController extends SetupController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
    public $layout='/layouts/main';
    public function actionIndex()
    {

        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->createUrl('site/login'));
        else {
            $menuModel = Dishtype::model()->findAll('t.parent = :parent', array(':parent' => 0));
            $expModel = Yii::app()->db->CreateCommand()
                ->select()
                ->from("expense")
                ->where('`table` = 0 and `status` != 0 and debt != 1')
                ->queryAll();
            $model = new Expense;
            $this->render('index', array(
                'model' => $model,
                'menuModel' => $menuModel,
                'expModel' => $expModel
            ));
        }
    }
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}