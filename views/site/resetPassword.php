<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
         

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Change Password</h6> 


                <div class="row">

                    <div class="col-lg-12">


                        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-info']) ?>
                            </div>

                        <?php ActiveForm::end(); ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

