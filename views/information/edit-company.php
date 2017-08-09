<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTerm;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
//


if (empty($model['sellers'][0]['state'])) {
    $state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$modelCompany->country])->asArray()->all(), 'id', 'state');
} else {
    $state = ArrayHelper::map(LookupState::find()->where(['id'=>$model['sellers'][0]['state']])->asArray()->all(), 'id', 'state');
}

$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');

//$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
$type_of_tax = ['VAT'=>'VAT','GST'=>'GST'];
//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Supplier Info';

$script = <<< JS
$(document).ready(function(){

    $('.companyoff').on('click', function () {

        var company_name = $(this).data('company_name');
        var company_registeration_no = $(this).data('company_registeration_no');
        var address = $(this).data('address');
        var zip_code = $(this).data('zip_code');
        var country = $(this).data('country');
        var state = $(this).data('state');
        var city = $(this).data('city');
        var telephone_no = $(this).data('telephone_no');
        var fax_no = $(this).data('fax_no');
        var email = $(this).data('email');
        var website = $(this).data('website');
        var type_of_tax = $(this).data('type_of_tax');
        var tax = $(this).data('tax');
        var term = $(this).data('term');
        var att = $(this).data('att');
        var att_email = $(this).data('att_email');
        var att_tel = $(this).data('att_tel');


        $('#companyname').val(company_name);
        $('#registrationno').val(company_registeration_no);
        $('#address').val(address);
        $('#zipcode').val(zip_code);
        $('#country').val(country);
        $('#state').val(state);
        $('#city').val(city);
        $('#telephoneno').val(telephone_no);
        $('#faxno').val(fax_no);
        $('#email').val(email);
        $('#website').val(website);
        $('#typeoftax').val(type_of_tax);
        $('#tax').val(tax);
        $('#term').val(term);
        $('#att').val(att);
        $('#att_email').val(att_email);
        $('#att_tel').val(att_tel);



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

        <?= $form->field($modelCompany, 'company_name')->textInput(['id'=>'companyname','value'=>$model['sellers'][0]['seller']]) ?>

        <?= $form->field($modelCompany, 'company_registeration_no')->textInput(['id'=>'registrationno','value'=>$model['sellers'][0]['company_registeration_no']]) ?>

        <?= $form->field($modelCompany, 'address')->textarea(['rows' => 6,'id' => 'address','value'=>$model['sellers'][0]['address']]) ?>

        <?= $form->field($modelCompany, 'zip_code')->textInput(['id'=>'zipcode','value'=>$model['sellers'][0]['zip_code']]) ?>

        <?= $form->field($modelCompany, 'country')->dropDownList(
            $country, 
        [
            'prompt' => '-Select Country-',
            'class' => 'form-control',
            'id' => 'country',
            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/source/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
            'options' => [$model['sellers'][0]['country'] => ['selected'=>true]]

        ]) ?>

        <?= $form->field($modelCompany, 'state')->dropDownList(
            $state, 
        [
            'prompt' => '-Select State-',
            'class' => 'form-control state',
            'id'=> 'state',
            'options' => [$model['sellers'][0]['state'] => ['selected'=>true]]

        ]) ?>

        <?= $form->field($modelCompany, 'city')->textInput(['id'=>'city','value'=>$model['sellers'][0]['city']]) ?>

    </div>
    <div class="col-lg-4">

        

        <?= $form->field($modelCompany, 'telephone_no')->textInput(['id'=>'telephoneno','value'=>$model['sellers'][0]['telephone_no']]) ?>

        <?= $form->field($modelCompany, 'fax_no')->textInput(['id'=>'faxno','value'=>$model['sellers'][0]['fax_no']]) ?>

        <?= $form->field($modelCompany, 'email')->textInput(['id'=>'email','value'=>$model['sellers'][0]['email']]) ?>

        <?= $form->field($modelCompany, 'website')->textInput(['id'=>'website','value'=>$model['sellers'][0]['website']]) ?>

        <?= $form->field($modelCompany, 'type_of_tax')->dropDownList(
            $type_of_tax, 
        [
            'prompt' => '-Select Type Of Tax',
            'class' => 'form-control',
            'id' => 'typeoftax',
            'options' => [$model['sellers'][0]['type_of_tax'] => ['selected'=>true]]

        ]) ?>

        <?= $form->field($modelCompany, 'tax')->textInput(['id'=>'tax','value'=>$model['sellers'][0]['tax']]) ?>

        <?= $form->field($modelCompany, 'term')->dropDownList(
            $term, 
        [
            'prompt' => '-Select Term-',
            'class' => 'form-control',
            'id' => 'term',
            'options' => [$model['sellers'][0]['term'] => ['selected'=>true]]

        ]) ?>

        <hr>
        <h2>Attention</h2>

        <?= $form->field($modelCompany, 'att')->textInput(['id'=>'att','value'=>$model['sellers'][0]['att']])->label('Attention To') ?>

        <?= $form->field($modelCompany, 'att_email')->textInput(['id'=>'att_email','value'=>$model['sellers'][0]['att_email']])->label('Email') ?>

        <?= $form->field($modelCompany, 'att_tel')->textInput(['id'=>'att_tel','value'=>$model['sellers'][0]['att_tel']])->label('Contact No') ?>

        


        <?= Html::submitButton($modelCompany->isNewRecord ? 'Submit' : 'Submit', [
        'class' => $modelCompany->isNewRecord ? 'btn btn-info' : 'btn btn-info',

        ]) ?>

    </div>

    <div class="col-lg-4" style="height: 560px; overflow-y: auto; overflow-x: auto;   border-left: solid #b7b7b7 1px;">

            <ul style="list-style-type: none; ">
            <?php foreach ($CompanyOffline as $key => $value) { ?>
                <li style="cursor: pointer;color: #2d85d2;" class="companyoff" 
                data-company_name="<?= $value['company_name']; ?>"  
                data-company_registeration_no="<?= $value['company_registeration_no']; ?>"  
                data-address="<?= $value['address']; ?>"  
                data-zip_code="<?= $value['zip_code']; ?>"  
                data-country="<?= $value['country']; ?>"  
                data-state="<?= $value['state']; ?>"  
                data-city="<?= $value['city']; ?>"  
                data-telephone_no="<?= $value['telephone_no']; ?>"  
                data-fax_no="<?= $value['fax_no']; ?>" 
                data-email="<?= $value['email']; ?>" 
                data-website="<?= $value['website']; ?>"
                data-type_of_tax="<?= $value['type_of_tax']; ?>" 
                data-tax="<?= $value['tax']; ?>"
                data-term="<?= $value['term']; ?>"  

                >
                <?= $value['company_name']; ?>
                <hr>

                    
                </li>

            <?php } ?>
           </ul>

    </div>
    
</div>
<?php ActiveForm::end(); ?>