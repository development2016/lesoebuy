<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use dosamigos\datepicker\DatePicker;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Pending';


?>
<h2><?= Html::encode($this->title) ?></h2>
<div class="row" style="max-height: 300px;overflow-y: scroll;">
<div class="col-lg-12 col-xs-12 col-sm-12">


    <?php foreach ($listnotify as $key => $value) { ?>


    <blockquote>

    <?php $imgProfile = User::find()->where(['account_name'=>$value['from_who']])->one(); 

        if (empty($imgProfile->img)) {
            
            $img = 'leso/leso.png';
        } else {
            $img = $imgProfile->img;
        }


    ?>

                <?php if (empty($value['remark'])) { ?>

                    <?= Html::a(
                    '
                    <div class="user-img"> <img src="'.Yii::$app->request->baseUrl.'/image/'.$img.'" alt="user" class="img-circle"></div>
                    <div class="mail-contnet">
                    <h5 style="color:#009efb;">'.$value['details'].'</h5>
                    <h6>Project No : '.$value['project_no'].'</h6>
                    <span class="mail-desc">From : '.$imgProfile->name.'</span>

                    </div>
                    ', 
                    [
                        'notification/get',
                        'id'=> (string)$value['_id'],

                    ]) ?>

                <?php } else { ?>

                    <?= Html::a(
                    '
                    <div class="user-img"> <img src="'.Yii::$app->request->baseUrl.'/image/'.$img.'" alt="user" class="img-circle"></div>
                    <div class="mail-contnet">
                    <h5 style="color:#009efb;">'.$value['details'].'</h5>
                    <h6>Project No : '.$value['project_no'].'</h6>
                    <span class="mail-desc">From : '.$imgProfile->name.'</span>

                    <span class="time">Remark : <b style="color:#dc3030;">'.$value['remark'].'</b></span>
                    </div>
                    ', 
                    [
                        'notification/get',
                        'id'=> (string)$value['_id'],

                    ]) ?>



                <?php } ?>


    	
    </blockquote>





    <?php } ?>


</div>