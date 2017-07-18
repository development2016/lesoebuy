<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Delivery Before';

$script = <<< JS
$(document).ready(function(){

 $('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
}); 
JS;
$this->registerJs($script);

?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">

         <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-xs-12 col-sm-12">
            <?= $form->field($model, 'sellers[delivery_before]')->textInput(['id'=>'mdate'])->label('Delivery Before') ?>

            
<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', [
                                'class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info',

                                ]) ?>

        </div>





        <?php ActiveForm::end(); ?>

</div>
