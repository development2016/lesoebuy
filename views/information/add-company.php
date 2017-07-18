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
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$modelCompany->country])->asArray()->all(), 'id', 'state');
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



}); 
JS;
$this->registerJs($script);

?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>
<div class="row">

    
    <div class="col-lg-4">

        <?= $form->field($modelCompany, 'company_name')->textInput(['id'=>'companyname']) ?>

        <?= $form->field($modelCompany, 'company_registeration_no')->textInput(['id'=>'registrationno']) ?>

        <?= $form->field($modelCompany, 'address')->textarea(['rows' => 6,'id' => 'address']) ?>

        <?= $form->field($modelCompany, 'zip_code')->textInput(['id'=>'zipcode']) ?>

        <?= $form->field($modelCompany, 'country')->dropDownList(
            $country, 
        [
            'prompt' => '-Select Country-',
            'class' => 'form-control',
            'id' => 'country',
            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/source/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
            //'options' => ['3' => ['selected'=>true]]

        ]) ?>

        <?= $form->field($modelCompany, 'state')->dropDownList(
            $state, 
        [
            'prompt' => '-Select State-',
            'class' => 'form-control',
            'id'=> 'state',

        ]) ?>

    </div>
    <div class="col-lg-4">

        <?= $form->field($modelCompany, 'city')->textInput(['id'=>'city']) ?>

        <?= $form->field($modelCompany, 'telephone_no')->textInput(['id'=>'telephoneno']) ?>

        <?= $form->field($modelCompany, 'fax_no')->textInput(['id'=>'faxno']) ?>

        <?= $form->field($modelCompany, 'email')->textInput(['id'=>'email']) ?>

        <?= $form->field($modelCompany, 'website')->textInput(['id'=>'website']) ?>

        <?= $form->field($modelCompany, 'type_of_tax')->dropDownList(
            $type_of_tax, 
        [
            'prompt' => '-Select Type Of Tax',
            'class' => 'form-control',
            'id' => 'typeoftax',
            'options' => ['GST' => ['selected'=>true]]

        ]) ?>

        <?= $form->field($modelCompany, 'tax')->textInput(['value'=>'6','id'=>'tax']) ?>

        <?= $form->field($modelCompany, 'term')->dropDownList(
            $term, 
        [
            'prompt' => '-Select Term-',
            'class' => 'form-control',
            'id' => 'term'

        ]) ?>


        <?= Html::submitButton($modelCompany->isNewRecord ? 'Submit' : 'Submit', [
        'class' => $modelCompany->isNewRecord ? 'btn btn-info' : 'btn btn-info',

        ]) ?>

    </div>
    
</div>
<?php ActiveForm::end(); ?>