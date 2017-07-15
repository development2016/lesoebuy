<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$script = <<< JS
$(document).ready(function(){

    $('#username').on("input", function() {
      var dInput = $(this).val(); 
      $('#acc_name').val(dInput);

    });


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




}); 
JS;
$this->registerJs($script);



/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-6 col-xs-12 col-sm-12">

        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'id'=>'username','class'=>'form-control username_to_search']) ?>
        <span class="show-username"></span>

        <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Password') ?>

        <label class="control-label">Role</label>
        <br>
        <?php if ($type == 'Buyer') { ?>


            <div class="checkbox">
              <label> 
                <input type="checkbox" value="3100" name="LookupRole[role_id][]">
                Buyer
              </label>
            </div>

            <div class="checkbox">
              <label> 
                <input type="checkbox" value="3200" name="LookupRole[role_id][]">
                Approver
              </label>
            </div>
            <div class="checkbox">
              <label> 
                <input type="checkbox" value="3400" name="LookupRole[role_id][]">
                User
              </label>
            </div>


        <?php } else if($type == 'Seller') { ?>
        
            <div class="checkbox">
              <label> 
                <input type="checkbox" value="2100" name="LookupRole[role_id][]">
                Seller
              </label>
            </div>


        <?php } ?>

        <hr>

        

    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">


        <label class="control-label">Branch</label>
        <br>

            <div class="radio">
              <label>
                <input type="radio" name="User[branch]" id="optionsRadios2" value="100" checked>
                LesoShoppe KL
              </label>
            </div>
        <?php foreach ($company->warehouses as $key => $value) { ?>

            <div class="radio">
              <label>
                <input type="radio" name="User[branch]" id="optionsRadios2" value="<?php echo $key ?>">
                <?php echo $value['warehouse_name'];?>
              </label>
            </div>

        <?php } ?>

        <hr>





        <label class="control-label" for="user-account_name">Account Name</label>
        <div class="input-group input-large">

            <input type="text" class="form-control" id="acc_name" name="User[account_name]" readonly>
            <span class="input-group-addon">
                <?php echo $company->asia_ebuy_no = substr($company->asia_ebuy_no,0, strrpos($company->asia_ebuy_no, '@')); ?>
            </span>
        </div>
        <br>


        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

