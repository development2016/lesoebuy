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

$this->title = 'Process';


?>
<h2><?= Html::encode($this->title) ?></h2>
<div class="row" style="max-height: 300px;overflow-y: scroll;">
<div class="col-lg-12 col-xs-12 col-sm-12">


    <?php $i=0; foreach ($listprocess as $key => $value) { $i++;?>


    <blockquote>
        <p><b>Project No : </b><?php echo $value['project_no']; ?></p>
        <p><b>Title : </b><?php echo $value['title']; ?></p>
        <p><b>Due Date : </b><span style="color: #ff0000;"><?php echo $value['due_date']; ?></span></p>
        <p><b>PR No : </b> <span style="color: #009efb; "><?= $value['sellers'][0]['purchase_requisition_no']; ?></span></p>
        <p><b>Status : </b> <span style="color: green;"><?= $value['sellers'][0]['status']; ?></span></p>
    </blockquote>





    <?php } ?>


</div>