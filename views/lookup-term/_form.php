<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LookupTerm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lookup-term-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'term')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
