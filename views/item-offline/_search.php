<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOfflineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-offline-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'item_code') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'brand') ?>

    <?= $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'specification') ?>

    <?php // echo $form->field($model, 'lead_time') ?>

    <?php // echo $form->field($model, 'validity') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'cit') ?>

    <?php // echo $form->field($model, 'shipping') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'enter_by') ?>

    <?php // echo $form->field($model, 'update_by') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
