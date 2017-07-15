<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
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

		<div class="form-group field-project-sellers-0-items-0-item_name">
		<label class="control-label" for="project-sellers-0-items-0-item_name">Item Name</label>
		<input type="text" id="project-sellers-0-items-0-item_name" class="form-control" name="Project[sellers][0][items][<?php echo $arrayItem ?>][item_name]" value="<?php echo $value['sellers'][0]['items'][$arrayItem]['item_name']; ?>">

		<div class="help-block"></div>
		</div>

    	
    <?php } ?>
<input type="hidden" name="arrayItem" value="<?php echo $arrayItem; ?>">

        
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
