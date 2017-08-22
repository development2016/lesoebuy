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
<div class="row">
    <div class="col-lg-6">

        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'id'=>'username','class'=>'form-control username_to_search']) ?>
        <span class="show-username"></span>

        <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Password') ?>

        <label class="control-label"><b>Role</b></label>
        <br>
        <?php if ($type == 'Buyer') { ?>

            <input type="checkbox" name="LookupRole[role_id][]" id="buyer" class="filled-in chk-col-light-green" value="3100" />
            <label for="buyer">Buyer</label>

            <input type="checkbox" name="LookupRole[role_id][]" id="approver" class="filled-in chk-col-light-green" value="3200" />
            <label for="approver">Approver</label>

            <input type="checkbox" name="LookupRole[role_id][]" id="user" class="filled-in chk-col-light-green" value="3400" />
            <label for="user">User</label>


        <?php } else if($type == 'Seller') { ?>

            <input type="checkbox" name="LookupRole[role_id][]" id="seller" class="filled-in chk-col-light-green" value="2100" />
            <label for="seller">Seller</label>


        <?php } ?>

        <hr>

        

    </div>
    <div class="col-lg-6">


        <label class="control-label"><b>Branch</b></label>
        <br>


            <input name="User[branch]" type="radio" id="kl" class="radio-col-light-green" value="100" />
            <label for="kl">LesoShoppe KL</label>




        <?php foreach ($company->warehouses as $key => $value) { ?>

            <input name="User[branch]" type="radio" id="<?php echo $key ?>" class="radio-col-light-green" value="<?php echo $key ?>" />
            <label for="<?php echo $key ?>"><?php echo $value['warehouse_name'];?></label>


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
            <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-info pull-right' : 'btn btn-info pull-right']) ?>
        </div>

    </div>

</div>
    <?php ActiveForm::end(); ?>

