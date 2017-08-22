<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'sellers') ?>

    <?= $form->field($model, 'due_date') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'project_no') ?>

    <?= $form->field($model, 'type_of_project') ?>

    <?= $form->field($model, 'date_create') ?>

    <?= $form->field($model, 'enter_by') ?>

    <?= $form->field($model, 'date_update') ?>

    <?= $form->field($model, 'url_myspot') ?>

    <?= $form->field($model, 'requester') ?>

    <?= $form->field($model, 'tax_value') ?>

    <?= $form->field($model, 'request_role') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
