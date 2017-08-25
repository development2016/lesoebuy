<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTypeOfBusiness;
use app\models\LookupBank;

$this->title = 'Registeration';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    var typingTimer;                //timer identifier
    var doneTypingInterval = 100;  //time in ms, 2 second for example  100 = 0.1 sec / 1000 = 1 sec


    $('.company_name_to_search').on('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });


    $('.company_name_to_search').on('keydown', function () {
      clearTimeout(typingTimer);
    });

    function doneTyping () {
        var inputVal = $('.company_name_to_search').val();

        $.ajax({
            type: 'POST',
            url: 'company-name',
            data: {value: inputVal},

            success: function(data) {

                $('.show-company').html(data);

            }

        })


    }


    var typingTimer2;                //timer identifier
    var doneTypingInterval2 = 100;  //time in ms, 2 second for example  100 = 0.1 sec / 1000 = 1 sec


    $('.company_registeration_no_to_search').on('keyup', function () {
      clearTimeout(typingTimer2);
      typingTimer2 = setTimeout(doneTyping2, doneTypingInterval2);
    });


    $('.company_registeration_no_to_search').on('keydown', function () {
      clearTimeout(typingTimer2);
    });

    function doneTyping2 () {
        var inputVal2 = $('.company_registeration_no_to_search').val();

        $.ajax({
            type: 'POST',
            url: 'registeration-no',
            data: {value: inputVal2},

            success: function(data) {

                $('.show-registeration-no').html(data);

            }

        })


    }

    var typingTimer3;                //timer identifier
    var doneTypingInterval3 = 100;  //time in ms, 2 second for example  100 = 0.1 sec / 1000 = 1 sec


    $('.username_to_search').on('keyup', function () {
      clearTimeout(typingTimer3);
      typingTimer3 = setTimeout(doneTyping3, doneTypingInterval3);
    });


    $('.username_to_search').on('keydown', function () {
      clearTimeout(typingTimer3);
    });

    function doneTyping3 () {
        var inputVal3 = $('.username_to_search').val();

        $.ajax({
            type: 'POST',
            url: 'username',
            data: {value: inputVal3},

            success: function(data) {

                $('.show-username').html(data);

            }

        })


    }

    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })




}); 
JS;
$this->registerJs($script);

$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model2->country])->asArray()->all(), 'id', 'state');
$type_of_business = ArrayHelper::map(LookupTypeOfBusiness::find()->asArray()->all(), 'type_of_business', 'type_of_business');
$bank = ArrayHelper::map(LookupBank::find()->asArray()->all(), 'bank', 'bank');


?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 

            </div>


            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"> 
                  <a class="nav-link active" data-toggle="tab" href="#active" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Active</span></a> 
                </li>
                <li class="nav-item"> 
                  <a class="nav-link" data-toggle="tab" href="#log" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Log</span></a> 
                </li>

            </ul>
            <?php $form = ActiveForm::begin(); ?>
            <div class="tab-content">
                <div class="tab-pane active" id="active" role="tabpanel">
                    <div class="p-20 ">

                        <?= $form->field($model2, 'company_name')->textInput(['id'=>'company_name','class'=>'form-control company_name_to_search','style'=>'text-transform:uppercase;']) ?>
                      <span class="show-company"></span>

                        <?= $form->field($model2, 'company_registeration_no')->textInput(['id'=>'company_registeration_no','class'=>'form-control company_registeration_no_to_search']) ?>
                      <span class="show-registeration-no"></span>

                        <?= $form->field($model2, 'address')->textarea(['rows' => 6,'id'=>''])->label('Address') ?>

                        <?= $form->field($model2, 'zip_code')->textInput(['id'=>'','class'=>'form-control']) ?>

                        <?= $form->field($model2, 'country')->dropDownList(
                                        $country, 
                                    [
                                        'prompt' => '-Select Country-',
                                        'class' => 'form-control',
                                        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/site/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

                                    ]) ?>


                        <?= $form->field($model2, 'state')->dropDownList(
                                        $state, 
                                    [
                                        'prompt' => '-Select State-',
                                        'class' => 'form-control',
                                        'id'=> 'state-id',

                                    ]) ?>

                        <?= $form->field($model2, 'city')->textInput(['id'=>'','class'=>'form-control']) ?>

                        <?= $form->field($model2, 'type_of_business')->dropDownList(
                            $type_of_business, 
                        [
                            'prompt' => '-Select Type Of Business-',
                            'class' => 'form-control',

                        ]) ?>


                        <?= $form->field($model2, 'tax_no')->textInput(['id'=>'','class'=>'form-control']) ?>

                    </div>
                </div>

                <div class="tab-pane  p-20" id="log" role="tabpanel">


                      <?= $form->field($model, 'email')->textInput(['id'=>'','class'=>'form-control']) ?>

                      <?= $form->field($model, 'username')->textInput(['id'=>'username_to_search','class'=>'form-control username_to_search']) ?>
                      <span class="show-username"></span>

                      <?= $form->field($model, 'password_hash')->passwordInput(['id'=>'','class'=>'form-control '])->label('Password') ?>

                      <?= Html::submitButton('Submit', ['class' => 'btn btn-info', 'name' => 'register-button']) ?>



                </div>
            </div>
            <?php ActiveForm::end(); ?>





            </div>
        </div>
    </div>
</div>