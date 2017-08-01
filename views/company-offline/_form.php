<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-6">

            <?= $form->field($model, 'company_name') ?>

            <?= $form->field($model, 'company_registeration_no') ?>

            <?= $form->field($model, 'address') ?>

            <?= $form->field($model, 'zip_code') ?>

            <?= $form->field($model, 'country') ?>

            <?= $form->field($model, 'state') ?>

            <?= $form->field($model, 'city') ?>

            <?= $form->field($model, 'telephone_no') ?>

        </div>

        <div class="col-md-6">


            <?= $form->field($model, 'fax_no') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'website') ?>

            <?= $form->field($model, 'tax') ?>

            <?= $form->field($model, 'type_of_tax') ?>

            <?= $form->field($model, 'date_create') ?>

            <?= $form->field($model, 'enter_by') ?>

            <?= $form->field($model, 'date_update') ?>

            <?= $form->field($model, 'update_by') ?>

            <?= $form->field($model, 'term') ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>



            
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>

    





    

