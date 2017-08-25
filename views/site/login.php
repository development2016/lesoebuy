<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-body">

    <?php $form = ActiveForm::begin([
        'id' => 'loginform',
        'options' => [
            'class' => 'form-horizontal form-material'
        ]
        /*'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],*/
    ]); ?>



        <a href="javascript:void(0)" class="text-center db">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/iconlogin.png" alt="Home" /><br/>
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/text2login.png" alt="Home" />
        </a>  


        
        <div class="form-group m-t-40">
          <div class="col-xs-12">
            <?= $form->field($model, 'username')->textInput(['class'=>'form-control','placeholder'=>'Username'])->label(false) ?>

          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>'Password'])->label(false) ?>
 
          </div>
        </div>
        <div class="form-group">
          <!--<div class="col-md-12">
            <div class="checkbox checkbox-primary pull-left p-t-0">
            // site/buyer
            </div>
            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> 
            </div> -->
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <?= Html::submitButton('Log In', ['class' => 'btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light', 'name' => 'login-button']) ?>

          </div>
        </div>




    <?php ActiveForm::end(); ?>

</div>
