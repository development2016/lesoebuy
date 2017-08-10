<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTerm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\DeliveryAddress */
/* @var $form yii\widgets\ActiveForm */
$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
/* @var $this yii\web\View */
?>

<div class="delivery-address-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'contact') ?>

    <?= $form->field($model, 'fax') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'warehouse_name') ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6,'id' => 'address']) ?>

    <?= $form->field($model, 'postcode') ?>

    <?= $form->field($model, 'location') ?>

    <?= $form->field($model, 'country')->dropDownList(
        $country, 
    [
        'prompt' => '-Select Country-',
        'class' => 'form-control',
        'id' => 'country',
        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['delivery-address/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
        //'options' => ['3' => ['selected'=>true]]

    ]) ?>

    <?= $form->field($model, 'state')->dropDownList(
        $state, 
    [
        'prompt' => '-Select State-',
        'class' => 'form-control state',
        'id'=> 'state',

    ]) ?>


    <?= $form->field($model, 'latitude') ?>

    <?= $form->field($model, 'longitude') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
