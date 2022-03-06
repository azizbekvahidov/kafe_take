<?php
class SetupController extends Controller{
    
    public function __construct($id, $module = null)
    {   
    //     try{
    //         $res = Yii::app()->db->CreateCommand()
    //         ->select()
    //         ->from("license")
    //         ->where("progType = 'kafe' and active = 1")
    //         ->queryRow();
    //     if(!empty($res)){
    //         $name = Yii::app()->config->get("name");
    //         $func = new Functions();
    //         $SN = $func->UniqueMachineID("C");
    //         $hash = $func->GetHash($name,$res["secretKey"],$SN);
    //         if($res["hash"] != $hash){
    //             $this->redirect("configure/index");
    //         }
    //     }
    //     else{
    //         $this->redirect("configure/index");
    //     }
    // }
    // catch(Exception $ex){
    //     print_r($ex->getMessage());
    // }
        parent::__construct($id, $module);
    }

}