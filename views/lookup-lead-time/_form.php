<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LookupLeadTime */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lookup-lead-time-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lead_time')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
