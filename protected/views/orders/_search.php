<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'order_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'expense_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'just_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'type',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'count',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'table_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
