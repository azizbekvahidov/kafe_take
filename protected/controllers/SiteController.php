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

    public function actionAvans(){
         $model = Yii::app()->db->CreateCommand()
             ->select()
             ->from("expense ex")
             ->where("ex.status = 0 and prepaid = 1 and order_date = '2000-01-01 00:00:00'" )
             ->queryAll();
         $this->render("avans",array(
             'model' => $model
         ));
    }


    public function archive_check(){
    }


    public function actionIndex()
    {

        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->createUrl('site/login'));
        else {
            $change = Yii::app()->db->CreateCommand()
                ->select()
                ->from("change c")
                ->join("employee e","e.employee_id = c.employee_id")
                ->where("c.status = 1")
                ->queryRow();
            if($change["employee_id"] != Yii::app()->user->getId()){
                $this->redirect("site/changeUser");
            }
            $menuModel = Dishtype::model()->findAll('t.parent = :parent', array(':parent' => 0));
            $expModel = Yii::app()->db->CreateCommand()
                ->select()
                ->from("expense")
                ->where('`table` = 0 and `status` != 0 and debt != 1')
                ->queryAll();
            $model = new Expense;
            $this->render('index', array(
                'model' => $model,
                'change' => $change,
                'menuModel' => $menuModel,
                'expModel' => $expModel
            ));
        }
    }

    public function actionChangeUser(){
        $this->render("changeUser");
    }

    public function endChange(){
        Yii::app()->db->createCommand()->update("change",array(
            "status" => 0,
            "end_time" => date("Y-m-d H:i:s"),
        ),"status  = 1");
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
		    $change = Yii::app()->db->CreateCommand()
		        ->select()
		        ->from("change")
		        ->where("status = 1")
		        ->queryRow();
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {

                if(empty($change)){
                    Yii::app()->db->createCommand()->insert("change",array(
                        "employee_id" => Yii::app()->user->getId(),
                        "status" => 1,
                        "start_time" => date("Y-m-d H:i:s"),
                    ));
                }
                $this->redirect(Yii::app()->user->returnUrl);
            }
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
    public function actionChangeLogout()
    {
        $this->closeChange();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    public function closeChange(){
	    $expense = new Expense();
        $change = Yii::app()->db->CreateCommand()
            ->select()
            ->from("change")
            ->where("status != 0")
            ->queryRow();
        $to = date("Y-m-d H:i:s");
        $from = $change["start_time"];

        $paidDebt = Yii::app()->db->CreateCommand()
            ->select("sum(ex.expSum) as expSum")
            ->from("debt d")
            ->join('expense ex','d.expense_id = ex.expense_id')
            ->where(' date(d.d_date) = :to ',array(':to'=>date("Y-m-d",strtotime($to))))
            ->queryRow();

        $avans = Yii::app()->db->createCommand()
            ->select("sum(t.prepaidSum) as prepaidSum")
            ->from("expense t")
            ->where('date(t.order_date) = :to AND t.prepaid = 1',array(':to'=>date("Y-m-d",strtotime($to))))
            ->queryRow();
        $term = 0;
        $percent = 0;
        $newModel = Yii::app()->db->createCommand()
            ->select("sum(t.expSum) as expSum, sum(t.terminal) as terminal")
            ->from("expense t")
            ->where('(date(t.order_date) = :to) AND t.prepCreate = 0 AND t.status != 1 AND t.debt != 1 AND t.kind != 1 AND t.prepaid != 1',array(':to'=>date("Y-m-d",strtotime($to))))
            ->queryRow();
        $debtpaid = Yii::app()->db->createCommand()
            ->select("sum(t.debtPayed) as debtPayed, sum(t.terminal) as terminal")
            ->from("expense t")
            ->where('date(t.order_date) = :to AND  t.debt != :debt AND t.kind != 1 AND t.prepaid != 1',array(':to'=>date("Y-m-d",strtotime($to)),':debt'=>0))
            ->queryRow();

        $completeavans = Yii::app()->db->createCommand()
            ->select("sum(expSum) as summ")
            ->from("prepaid")
            ->where("date(prepDate) = :to ",array(":to"=>date("Y-m-d",strtotime($to))))
            ->queryRow();

        $department = Yii::app()->db->createCommand()
            ->select('')
            ->from('department')
            ->queryAll();

        foreach($department as $val){

            $cost = $expense->getDepCost($val["department_id"],date("Y-m-d",strtotime($to)),date("Y-m-d",strtotime($to)));
            $depSum = $expense->getDepIncome($val["department_id"],date("Y-m-d",strtotime($to)),date("Y-m-d",strtotime($to)));

            $mdepBalance = Yii::app()->db->CreateCommand()
                ->select()
                ->from("mdepbalance")
                ->where("department_id = ".$val["department_id"]." and b_date = '".date("Y-m-d",strtotime($to))."'")
                ->queryRow();
            if(empty($mdepBalance)){
                Yii::app()->db->CreateCommand()->insert("mdepbalance",array(
                    'b_date' => date("Y-m-d",strtotime($to)),
                    'costPrice' => $cost,
                    'department_id' => $val["department_id"],
                    'expSum' => $depSum,
                ));
            }
            else{
                Yii::app()->db->CreateCommand()->update("mdepbalance",array(
                    'costPrice' => $cost,
                    'expSum' => $depSum,
                ),"department_id = ".$val["department_id"]." and b_date = '".date("Y-m-d",strtotime($to))."'");
            }
        }

        Yii::app()->db->createCommand()->update("change",array(
            'status' => 0,
            'end_time' => date("Y-m-d H:i:s"),
            'changeSum' => $paidDebt["expSum"] + $avans["prepaidSum"] + $completeavans["summ"] + $newModel["expSum"] + $debtpaid["debtPayed"],
        ),"status = 1");
    }
}