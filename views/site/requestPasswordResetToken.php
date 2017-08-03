<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
         

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Please Make Sure That Is Your Username Proceed To Change Password !</h6> 


                <div class="row">

                    <div class="col-lg-12">

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true,'value'=>Yii::$app->user->identity->username,'readOnly'=>'readOnly']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-info']) ?>
                </div>

            <?php ActiveForm::end(); ?>



                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

