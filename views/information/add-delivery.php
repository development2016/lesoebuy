<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$country])->asArray()->all(), 'id', 'state');

//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Delivery';

$script = <<< JS
$(document).ready(function(){

    $('.warehouse').on('click', function () {
        var person_in_charge = $(this).data('person_in_charge');
        var contact = $(this).data('contact');
        var country = $(this).data('country');
        var state = $(this).data('state');
        var location = $(this).data('location');
        var warehouse_name = $(this).data('warehouse_name');
        var address = $(this).data('address');
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        var email = $(this).data('email');



        $('#person_in_charge').val(person_in_charge);
        $('#contact').val(contact);
        $('#country').val(country);
        $('#state').val(state);
        $('#location').val(location);
        $('#warehouse_name').val(warehouse_name);
        $('#address').val(address);
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        $('#email').val(email);

        $.ajax({
            type: 'POST',
            url: 'get',
            data: {state_id: state},
            success: function(data) {
                $(".state").html(data);
   

            }

        })



    });


}); 
JS;
$this->registerJs($script);

?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
         
        <div class="col-lg-4">
           

            <?= $form->field($model, 'sellers[warehouses][person_in_charge]')->textInput(['id'=>'person_in_charge'])->label('Person In Charge') ?>

            <?= $form->field($model, 'sellers[warehouses][contact]')->textInput(['id'=>'contact'])->label('Contact') ?>

            <?= $form->field($model, 'sellers[warehouses][email]')->textInput(['id'=>'email'])->label('Email') ?>

            <?= $form->field($model, 'sellers[warehouses][country]')->dropDownList(
                $country, 
            [
                'prompt' => '-Select Country-',
                'class' => 'form-control',
                'id' => 'country',
                'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',

            ])->label('Country') ?>

            <?= $form->field($model, 'sellers[warehouses][state]')->dropDownList(
                $state, 
            [
                'prompt' => '-Select State-',
                'class' => 'form-control state',
                'id'=> 'state',

            ])->label('State') ?>


            <?= $form->field($model, 'sellers[warehouses][location]')->textInput(['id'=>'location'])->label('City') ?>

        </div>

        <div class="col-lg-4">


            <?= $form->field($model, 'sellers[warehouses][warehouse_name]')->textInput(['id'=>'warehouse_name'])->label('Warehouse Name') ?>

            <?= $form->field($model, 'sellers[warehouses][address]')->textarea(['id'=>'address','rows' => 6])->label('Address') ?>

            <?= $form->field($model, 'sellers[warehouses][latitude]')->textInput(['id'=>'latitude'])->label('Latitude') ?>

            <?= $form->field($model, 'sellers[warehouses][longitude]')->textInput(['id'=>'longitude'])->label('Longitude') ?>



            <div class="form-group">

                <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', [
                'class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info',
                ]) ?>

            </div>

            
        </div>
        <?php ActiveForm::end(); ?>



        <div class="col-lg-4" style="height: 560px; overflow-y: auto; overflow-x: auto;   border-left: solid #b7b7b7 1px;">

            <ul style="list-style-type: none; ">
            <?php foreach ($companyBuyer->warehouses as $key => $value) { ?>
                <li style="cursor: pointer;color: #2d85d2;" class="warehouse" 
                data-person_in_charge="<?= $value['person_in_charge']; ?>"  
                data-contact="<?= $value['contact']; ?>"  
                data-country="<?= $value['country']; ?>"  
                data-state="<?= $value['state']; ?>"  
                data-location="<?= $value['location']; ?>"  
                data-warehouse_name="<?= $value['warehouse_name']; ?>"  
                data-address="<?= $value['address']; ?>"  
                data-latitude="<?= $value['latitude']; ?>"  
                data-longitude="<?= $value['longitude']; ?>" 
                data-email="<?= $value['email']; ?>"  

                >
                <?= $value['person_in_charge']; ?>
                <br>
                <?= $value['contact']; ?>
                <br>
                <?= $value['warehouse_name']; ?>
                <hr>

                    
                </li>

            <?php } ?>
           </ul>

    </div>
</div>
