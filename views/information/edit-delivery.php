<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
//$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$country])->asArray()->all(), 'id', 'state');

if (empty($model['sellers'][0]['warehouses'][0]['state'])) {
    $state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$country])->asArray()->all(), 'id', 'state');

} else {
    $state = ArrayHelper::map(LookupState::find()->where(['id'=>$model['sellers'][0]['warehouses'][0]['state']])->asArray()->all(), 'id', 'state');


}


//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Delivery';

$script = <<< JS
$(document).ready(function(){

    $('.clear').on('click', function () {

        $('#person_in_charge').val('');
        $('#contact').val('');
        $('#country').val('');
        $('#state').val('');
        $('#location').val('');
        $('#warehouse_name').val('');
        $('#address').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        $('#email').val('');
        $('#fax').val('');
        $('#postcode').val('');
        $('#ids').val('');



    });





    $('.warehouse').on('click', function () {

        var contact = $(this).data('contact');
        var country = $(this).data('country');
        var state = $(this).data('state');
        var location = $(this).data('location');
        var warehouse_name = $(this).data('warehouse_name');
        var address = $(this).data('address');
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        var email = $(this).data('email');
        var fax = $(this).data('fax');
        var postcode = $(this).data('postcode');
         var ids = $(this).data('ids');


        $('#contact').val(contact);
        $('#country').val(country);
        $('#state').val(state);
        $('#location').val(location);
        $('#warehouse_name').val(warehouse_name);
        $('#address').val(address);
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        $('#email').val(email);
        $('#fax').val(fax);
        $('#postcode').val(postcode);
        $('#ids').val(ids);

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
           

            <?= $form->field($model, 'sellers[warehouses][person_in_charge]')->textInput(['id'=>'person_in_charge','value'=>Yii::$app->user->identity->name])->label('Person In Charge') ?>

            <?= $form->field($model, 'sellers[warehouses][contact]')->textInput(['id'=>'contact','value'=>$model['sellers'][0]['warehouses'][0]['contact']])->label('Contact') ?>


            <?= $form->field($model, 'sellers[warehouses][fax]')->textInput(['id'=>'fax','value'=>$model['sellers'][0]['warehouses'][0]['fax']])->label('Fax') ?>

            <?= $form->field($model, 'sellers[warehouses][email]')->textInput(['id'=>'email','value'=>$model['sellers'][0]['warehouses'][0]['email']])->label('Email') ?>

            <?= $form->field($model, 'sellers[warehouses][country]')->dropDownList(
                $country, 
            [
                'prompt' => '-Select Country-',
                'class' => 'form-control',
                'id' => 'country',
                'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
                'options' => [$model['sellers'][0]['warehouses'][0]['country'] => ['selected'=>true]]

            ])->label('Country') ?>

            <?= $form->field($model, 'sellers[warehouses][state]')->dropDownList(
                $state, 
            [
                'prompt' => '-Select State-',
                'class' => 'form-control state',
                'id'=> 'state',
                'options' => [$model['sellers'][0]['warehouses'][0]['state'] => ['selected'=>true]]
        

            ])->label('State') ?>


            <?= $form->field($model, 'sellers[warehouses][location]')->textInput(['id'=>'location','value'=>$model['sellers'][0]['warehouses'][0]['location']])->label('City') ?>

        </div>

        <div class="col-lg-4">


            <?= $form->field($model, 'sellers[warehouses][warehouse_name]')->textInput(['id'=>'warehouse_name','value'=>$model['sellers'][0]['warehouses'][0]['warehouse_name']])->label('Warehouse Name') ?>

            <?= $form->field($model, 'sellers[warehouses][address]')->textarea(['id'=>'address','rows' => 6,'value'=>$model['sellers'][0]['warehouses'][0]['address']])->label('Address') ?>


            <?= $form->field($model, 'sellers[warehouses][postcode]')->textInput(['id'=>'postcode','value'=>$model['sellers'][0]['warehouses'][0]['postcode']])->label('Postcode') ?>

            <?= $form->field($model, 'sellers[warehouses][latitude]')->textInput(['id'=>'latitude','value'=>$model['sellers'][0]['warehouses'][0]['latitude']])->label('Latitude') ?>

            <?= $form->field($model, 'sellers[warehouses][longitude]')->textInput(['id'=>'longitude','value'=>$model['sellers'][0]['warehouses'][0]['longitude']])->label('Longitude') ?>



            <div class="form-group">

                <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', [
                'class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info',
                ]) ?>

                <button type="button" id="clear" class="btn waves-effect waves-light btn-warning pull-right clear">Clear</button>
            </div>

            <?= $form->field($model, 'sellers[warehouses][_id]')->textInput(['id'=>'ids','value'=>'exits'])->label(false) ?>

            
        </div>
        <?php ActiveForm::end(); ?>



        <div class="col-lg-4" style="height: 560px; overflow-y: auto; overflow-x: auto;   border-left: solid #b7b7b7 1px;">

            <ul style="list-style-type: none; ">
            <?php foreach ($companyBuyer as $key => $value) { ?>
                <li style="cursor: pointer;color: #2d85d2;" class="warehouse" 

                data-ids="<?= $value['_id']; ?>"
                data-contact="<?= $value['contact']; ?>"  
                data-country="<?= $value['country']; ?>"  
                data-state="<?= $value['state']; ?>"  
                data-location="<?= $value['location']; ?>"  
                data-warehouse_name="<?= $value['warehouse_name']; ?>"  
                data-address="<?= $value['address']; ?>"  
                data-latitude="<?= $value['latitude']; ?>"  
                data-longitude="<?= $value['longitude']; ?>" 
                data-email="<?= $value['email']; ?>" 
                data-fax="<?= $value['contact']; ?>"   
                data-postcode="<?= $value['postcode']; ?>"   


                >

                <b><?= $value['warehouse_name']; ?></b>
                <br>
                <?= $value['address']; ?>,<?= $value['postcode']; ?>,<?= $value['location']; ?>,<?= $value['state']; ?>,<?= $value['country']; ?>
                <br>
                <?= $value['contact']; ?>
                
                <hr>

                    
                </li>

            <?php } ?>
           </ul>

    </div>
</div>
