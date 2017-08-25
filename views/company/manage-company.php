<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTypeOfBusiness;
use app\models\LookupBank;
use app\models\LookupTerm;


$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
$type_of_business = ArrayHelper::map(LookupTypeOfBusiness::find()->asArray()->all(), 'type_of_business', 'type_of_business');
$bank = ArrayHelper::map(LookupBank::find()->asArray()->all(), 'bank', 'bank');
$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');

$script = <<< JS
$(document).ready(function(){

    $('.uploads').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

}); 
JS;
$this->registerJs($script);

$this->title = strtoupper('Manage Company');
?>


<div class="row">
  <div class="col-md-12">
      <div class="card">
        <div class="card-body">
   
        <?= Html::a('Upload Logo <i class="fa fa-upload"></i>',FALSE, ['value'=>Url::to(['company/upload','company_id'=>$company_id]),'class' => 'btn btn-info pull-right uploads','id'=>'','title'=>'Upload Logo','style'=>'color:#fff;']) ?>
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            <h6 class="card-subtitle">Description About Panel</h6> 


            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-lg-6">

                    <?php if (empty($model->logo)) { ?>
                    
                    <?php } else { ?>

                        <img src="<?php echo Yii::$app->request->baseUrl;?>/<?php echo $model->logo ?>" class="img-responsive" alt="" />

                    <?php } ?>




                        <?= $form->field($model, 'asia_ebuy_no')->textInput(['maxlength' => true,'readonly'=>true])->label('System Registration') ?>

                        <?= $form->field($model, 'company_name') ?>

                        <?= $form->field($model, 'company_registeration_no') ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'website') ?>

                        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'country')->dropDownList(
                            $country, 
                        [
                            'prompt' => '-Select Country-',
                            'class' => 'form-control',
                            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

                        ]) ?>

                        <?= $form->field($model, 'state')->dropDownList(
                            $state, 
                        [
                            'prompt' => '-Select State-',
                            'class' => 'form-control',
                            'id'=> 'state-id',

                        ]) ?>

                        <?= $form->field($model, 'city') ?>

                        <?= $form->field($model, 'zip_code') ?>

                </div>


                <div class="col-lg-6">



                        <?= $form->field($model, 'tax_no') ?>

                        <?= $form->field($model, 'telephone_no') ?>

                        <?= $form->field($model, 'fax_no') ?>

                        <?= $form->field($model, 'term')->dropDownList(
                            $term, 
                        [
                            'prompt' => '-Select Term-',
                            'class' => 'form-control',

                        ]) ?>


                        <?= $form->field($model, 'type_of_business')->dropDownList(
                            $type_of_business, 
                        [
                            'prompt' => '-Select Type Of Business-',
                            'class' => 'form-control',

                        ]) ?>

                        <?= $form->field($model, 'bank')->dropDownList(
                            $bank, 
                        [
                            'prompt' => '-Select Bank-',
                            'class' => 'form-control',

                        ]) ?>

                        <?= $form->field($model, 'bank_account_name') ?>

                        <?= $form->field($model, 'bank_account_no') ?>

                
                        <?= $form->field($model, 'keyword')->textarea(['rows' => 6]) ?>

                        <div class="form-group pull-right">
                            <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
                        </div>


                    
                </div>
                
            </div>


            <?php ActiveForm::end(); ?>

        </div>
      </div>
  </div>
</div>