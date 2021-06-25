<?php

/**
 * This is the model class for table "dep_realize".
 *
 * The followings are the available columns in table 'dep_realize':
 * @property integer $dep_realize_id
 * @property integer $dep_faktura_id
 * @property integer $prod_id
 * @property integer $price
 * @property integer $count
 */
class DepRealize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dep_realize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dep_faktura_id, prod_id, price', 'numerical', 'integerOnly'=>true),
            array('count', 'numerical'),
			/*
			//Example username
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u',
                 'message'=>'Username can contain only alphanumeric
                             characters and hyphens(-).'),
          	array('username','unique'),
          	*/
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dep_realize_id, dep_faktura_id, prod_id, price, count', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'product'=>array(self::BELONGS_TO,'Products','prod_id'),
            'faktura'=>array(self::BELONGS_TO,'DepFaktura','dep_faktura_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dep_realize_id' => 'Dep Realize',
			'dep_faktura_id' => 'Dep Faktura',
			'prod_id' => 'Prod',
			'price' => 'Price',
			'count' => 'Count',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('dep_realize_id',$this->dep_realize_id);
		$criteria->compare('dep_faktura_id',$this->dep_faktura_id);
		$criteria->compare('prod_id',$this->prod_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DepRealize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave()
    {
        $userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;

		if($this->isNewRecord)
        {

        }else{

        }


        return parent::beforeSave();
    }

    public function beforeDelete () {
		$userId=0;
		if(null!=Yii::app()->user->id) $userId=(int)Yii::app()->user->id;

        return false;
    }

    public function afterFind()    {

        parent::afterFind();
    }


	public function defaultScope()
    {
    	/*
    	//Example Scope
    	return array(
	        'condition'=>"deleted IS NULL ",
            'order'=>'create_time DESC',
            'limit'=>5,
        );
        */
        $scope=array();


        return $scope;
    }

	public function realize_prod($dates){
        $sum = 0;
		$model = DepFaktura::model()->with('realizedProd')->findAll('date(real_date) = :real_date',array(':real_date'=>$dates));

		foreach ( $model as $value ) {
			foreach ( $value->getRelated( 'realizedProd' ) as $val ) {
				$sum = $sum + $val->count*$this->getRealized($val->prod_id,$dates);
			}
		}
        return $sum;
	}

    public function getRealized($id,$dates){
	    $dates = date('Y-m-d H:i:s',strtotime($dates)+86400);
        $model = Faktura::model()->with('realize')->find(array(
            'condition'=>'t.realize_date < "'.$dates.'"'.' AND realize.prod_id='.$id ,
            'order'=>'t.realize_date DESC',

        ));
        if(!empty($model))
            foreach ($model->getRelated('realize') as $val) {
                $price = $val->price;
            }
        else
            $price = 0;
        return $price;

    }

		public function getDepProdCurCount($depId,$dates){

	        $function = new Functions();
	        $stuff = new Halfstaff();
	        //Количественный расчет по отделам
	        /*$fromDate = date("Y-m-d",strtotime($dates)-86400);

	            $depId = $depId;
	            $depIn = array();
	            $depOut = array();
	            $inProduct = array();
	            $instuff = array();
	            $endProduct = array();
	            $endStuff = array();
	            $outProduct = array();
	            $outStuff = array();
	            $outStuffProd = array();
	            //расход продуктов в другой отдел
	            $depOut = $function->depMoveOut($depId,$dates,$fromDate);
	            //приход продуктов из других отделов
	            $depIn = $function->depMoveIn($depId,$dates,$fromDate);
	            $dish = new Expense();

	            $inProduct = $function->depInProducts($depId,$dates,$fromDate);

	            //Приход загатовок в отдел и расход их продуктов

	            $outProduct = $dish->getDishProd($depId,$dates,$dates);*/
	            $curProd = Yii::app()->db->createCommand()
	                ->select('*')
	                ->from('storage_dep')
	                ->where('department_id = :depId AND prod_type = :type',array(':depId'=>$depId,':type'=>1))
	                ->queryAll();
	            foreach($curProd as $value){
	                $endProduct[$value['prod_id']] = + $value['cnt'];
	            }
                return $endProduct;
		}

		public function getDepStuffCurCount($depId,$dates){

	        $function = new Functions();
	        $stuff = new Halfstaff();
	        //Количественный расчет по отделам
	        $fromDate = date("Y-m-d",strtotime($dates)-86400);

          $depId = $depId;
          $depIn = array();
          $depOut = array();
          $inProduct = array();
          $instuff = array();
          $endProduct = array();
          $endStuff = array();
          $outProduct = array();
          $outStuff = array();
          $outStuffProd = array();
          $outDishStuff = $dish->getDishStuff($depId,$dates,$dates);

          //Приход загатовок в отдел и расход их продуктов
          $instuff = $function->depInStuff($depId,$dates,$fromDate);

          $outStuffProd = $function->depOutStuffProd($depId,$dates,$fromDate);
          //Приход и расход загатовок в отдел, расход их продуктов
          $outStuff = $function->depOutStuff($depId,$dates,$fromDate);
          $outStuff = $stuff->sumArray($outStuff,$outDishStuff);

          $inexpense = new Inexpense();
          $depStuffIn = $inexpense->getDepIn($depId,$dates,$fromDate);
          $depStuffOut = $inexpense->getDepOut($depId,$dates,$fromDate);
          $curStuff = Yii::app()->db->createCommand()
              ->select('')
              ->from('dep_balance db')
              ->where('db.b_date = :dates AND db.department_id = :depId AND db.type = :type',array(':dates'=>$dates,':depId'=>$depId,':type'=>2))
              ->queryAll();
					foreach($curStuff as $value){
              $endStuff[$value['prod_id']] = ($value['startCount'] + $instuff[$value['prod_id']]+$depStuffIn[$value['prod_id']]-$outStuff[$value['prod_id']]-$depStuffOut[$value['prod_id']]);
          }

					return $endStuff;
		}
}
