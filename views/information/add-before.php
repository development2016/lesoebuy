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



?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">

         <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-xs-12 col-sm-12">
            <?= $form->field($model, 'sellers[delivery_before]')->widget(
                DatePicker::className(), [
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                       // 'placeholder' => 'hah'
                    ]
            ])->label('Delivery Before');?>
            
<?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', [
                                'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary',

                                ]) ?>

        </div>





        <?php ActiveForm::end(); ?>

</div>
