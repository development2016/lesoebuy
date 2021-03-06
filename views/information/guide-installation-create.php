<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Installation';

?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="ecommerce-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php foreach ($data as $key => $value) { ?>


		<div class="form-group field-project-sellers-0-items-0-installation_price">
		  <label class="control-label" for="project-sellers-0-items-0-installation_price">Installation Price</label>
		  <input type="text" id="project-sellers-0-items-0-installation_price" class="form-control" name="Project[sellers][0][installation_price]" >

		  <div class="help-block"></div>
		</div>


    	
    <?php } ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
