<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\LookupCountry;
use app\models\LookupState;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\LookupTerm;


$script = <<< JS
$(document).ready(function(){

 $('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
}); 
JS;
$this->registerJs($script);

$this->title = 'Create PR';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
  	<div class="col-md-12">
      	<div class="card">
        	<div class="card-body">

            	<h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            	<h6 class="card-subtitle">Description About Panel</h6> 

	        	<?php $form = ActiveForm::begin(); ?>

				    <?= $form->field($model3, 'title')->textInput(['id'=>'id-title','placeholder'=>'-Buy What From Who For Who-']) ?>

				    <?= $form->field($model3, 'description')->textarea(['rows' => 6]) ?>

				    <?= $form->field($model3, 'due_date')->textInput(['id'=>'mdate']) ?>

					<?= Html::submitButton($model3->isNewRecord ? 'Save' : 'Save', [
							    'class' => $model3->isNewRecord ? 'btn btn-info pull-right' : 'btn btn-info',

							    ]) ?>

	        	<?php ActiveForm::end(); ?>




        	</div>
       	</div>
    </div>
</div>




