<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupLeadTime;
use app\models\LookupValidity;

$lead = ArrayHelper::map(LookupLeadTime::find()->asArray()->all(),'id','lead_time'); 
$validity = ArrayHelper::map(LookupValidity::find()->asArray()->all(),'id','validity');

$yesorno = ['Yes'=>'Yes','No'=>'No'];

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-6">

    <?= $form->field($model, 'item_code') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'brand') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'specification')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lead_time')->dropDownList($lead, 
        [
            'prompt' => '-Please Choose-',
            'id' => 'lead-time',

        ])->label('Lead Time') ?>


        </div>

        <div class="col-md-6">

    <?= $form->field($model, 'cost') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'cit')->dropDownList($yesorno, 
        [
            'prompt' => '-Please Choose-',
            'id' => 'install',

        ])->label('C.I.T') ?>

    <?= $form->field($model, 'shipping')->dropDownList($yesorno, 
        [
            'prompt' => '-Please Choose-',
            'id' => 'shipping',

        ])->label('Shipping Charge') ?>


    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>


            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
            </div>



            
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>
