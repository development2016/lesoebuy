<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupLeadTime;

$lead = LookupLeadTime::find()->all(); 


//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Edit Info';

?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="ecommerce-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php foreach ($data as $key => $value) { ?>

		<div class="form-group field-project-sellers-0-items-0-brand">
			<label class="control-label" for="project-sellers-0-items-0-brand">Brand</label>
			<input type="text" id="project-sellers-0-items-0-brand" class="form-control" name="Project[sellers][0][items][<?php echo $arrayItem ?>][brand]" value="<?php echo $value['sellers'][0]['items'][$arrayItem]['brand']; ?>">

			<div class="help-block"></div>
		</div>

		<div class="form-group field-project-sellers-0-items-0-model">
			<label class="control-label" for="project-sellers-0-items-0-model">Model</label>
			<input type="text" id="project-sellers-0-items-0-model" class="form-control" name="Project[sellers][0][items][<?php echo $arrayItem ?>][model]" value="<?php echo $value['sellers'][0]['items'][$arrayItem]['model']; ?>">

			<div class="help-block"></div>
		</div>

		<div class="form-group field-project-sellers-0-items-0-specification">
			<label class="control-label" for="project-sellers-0-items-0-specification">Specification</label>
			<textarea id="project-sellers-0-items-0-specification" class="form-control" name="Project[sellers][0][items][<?php echo $arrayItem ?>][specification]" rows="6"><?php echo $value['sellers'][0]['items'][$arrayItem]['specification']; ?></textarea>

			<div class="help-block"></div>
		</div>

		<div class="form-group field-project-sellers-0-items-0-lead">
			<label class="control-label" for="project-sellers-0-items-0-lead">Lead Time</label>



			<select class="form-control" name="Project[sellers][0][items][<?php echo $arrayItem ?>][lead_time]">
			    <?php foreach ($lead as $key_lead => $value_lead) {?>
			        <option value="<?php echo $value_lead['id']?>"

			        <?php if($value_lead['id'] == $value['sellers'][0]['items'][$arrayItem]['lead_time']) 
			        	echo " selected"?>  

			        >


			        <?php echo $value_lead['lead_time'];?>
			        	
			        </option>
			    <?php } ?>
			</select>

			<div class="help-block"></div>
		</div>

		<div class="form-group field-project-sellers-0-items-0-remark">
		<label class="control-label" for="project-sellers-0-items-0-remark">Remark</label>
		<textarea id="project-sellers-0-items-0-remark" class="form-control" placeholder="Stock - Selling Price - Profit %" name="Project[sellers][0][items][<?php echo $arrayItem ?>][remark]" rows="6"><?php echo $value['sellers'][0]['items'][$arrayItem]['remark']; ?></textarea>

		<div class="help-block"></div>
		</div>
    	
    <?php } ?>
<input type="hidden" name="arrayItem" value="<?php echo $arrayItem; ?>">

        
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
