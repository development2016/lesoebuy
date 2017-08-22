<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTerm;
use yii\helpers\ArrayHelper;

$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');

$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */
/* @var $form yii\widgets\ActiveForm */
$type_of_tax = ['VAT'=>'VAT','GST'=>'GST'];
?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-6">

            <?= $form->field($model, 'company_name') ?>

            <?= $form->field($model, 'company_registeration_no') ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 6,'id' => 'address']) ?>

            <?= $form->field($model, 'zip_code') ?>

            <?= $form->field($model, 'country')->dropDownList(
                $country, 
            [
                'prompt' => '-Select Country-',
                'class' => 'form-control',
                'id' => 'country',
                'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['company-offline/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
                //'options' => ['3' => ['selected'=>true]]

            ]) ?>

            <?= $form->field($model, 'state')->dropDownList(
                $state, 
            [
                'prompt' => '-Select State-',
                'class' => 'form-control state',
                'id'=> 'state',

            ]) ?>

            <?= $form->field($model, 'city') ?>

            <?= $form->field($model, 'telephone_no') ?>

        </div>

        <div class="col-md-6">


            <?= $form->field($model, 'fax_no') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'website') ?>

            <?= $form->field($model, 'type_of_tax')->dropDownList(
                $type_of_tax, 
            [
                'prompt' => '-Select Type Of Tax',
                'class' => 'form-control',
                'id' => 'typeoftax',
                'options' => ['GST' => ['selected'=>true]]

            ]) ?>

            <?= $form->field($model, 'tax')->textInput(['value'=>'6','id'=>'tax']) ?>

            <?= $form->field($model, 'term')->dropDownList(
                $term, 
            [
                'prompt' => '-Select Term-',
                'class' => 'form-control',
                'id' => 'term'

            ]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
            </div>



            
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>

    





    

