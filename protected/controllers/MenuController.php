<?php

class MenuController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

    public function actionSearchList(){
        $txt = $_GET["txt"];
        $menuList = array();
        $price = new Prices();
        $dates = date('Y-m-d');
        $dishModel = Yii::app()->db->createCommand()
            ->select()
            ->from("menu m")
            ->join("dishes d","d.dish_id = m.just_id")
            ->where("m.type = 1 and d.name like '%".$txt."%'")
            ->queryAll();
        $stuffModel = Yii::app()->db->createCommand()
            ->select()
            ->from("menu m")
            ->join("halfstaff h","h.halfstuff_id = m.just_id")
            ->where("m.type = 2 and h.name like '%".$txt."%'")
            ->queryAll();
        $prodModel = Yii::app()->db->createCommand()
            ->select()
            ->from("menu m")
            ->join("products p","p.product_id = m.just_id")
            ->where("m.type = 3 and p.name like '%".$txt."%'")
            ->queryAll();
//        $dishModel = Menu::model()->with('dish')->findAll();
//        $stuffModel = Menu::model()->with('halfstuff')->findAll();
//        $prodModel = Menu::model()->with('products')->findAll();

        foreach ($dishModel as $val) {
            $menuList['dish_'.$val["just_id"]] = $val['name']."_".$price->getPrice($val["just_id"],$val["mType"],$val["type"],$dates);
        }
        foreach ($stuffModel as $val) {
            $menuList['stuff_'.$val["just_id"]] = $val['name']."_".$price->getPrice($val["just_id"],$val["mType"],$val["type"],$dates);
        }
        foreach ($prodModel as $val) {
            $menuList['prod_'.$val["just_id"]] = $val['name']."_".$price->getPrice($val["just_id"],$val["mType"],$val["type"],$dates);
        }
        echo json_encode($menuList);
    }
}