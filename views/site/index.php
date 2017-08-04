<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use app\models\Notification;
$this->title = 'Dashboard';

$notify = Notification::notify();


?>

    <?php if(Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert"></button>
             <?php echo  Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php } ?>



<div class="row m-t-40">
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-inverse card-info">
            <div class="box text-center">
                <h1 class="font-light text-white"><?=  $notify; ?></h1>
                <h6 class="text-white">Pending Notification</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-dark card-inverse">
            <div class="box text-center">
                <h1 class="font-light text-white">-</h1>
                <h6 class="text-white">Not Defined</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-inverse card-dark">
            <div class="box text-center">
                <h1 class="font-light text-white">-</h1>
                <h6 class="text-white">Not Defined</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-inverse card-dark">
            <div class="box text-center">
                <h1 class="font-light text-white">-</h1>
                <h6 class="text-white">Not Defined</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
      






<div class="row">


    <div class="col-lg-4 col-xlg-3">
        <!-- Column -->
        <div class="card earning-widget">
            <div class="card-header">

                <h4 class="card-title m-b-0">List Staff (<b>Online</b>)</h4>
            </div>
            <div class="card-block b-t collapse show">
                <table class="table v-middle no-border">
                    <tbody>
                    <?php foreach ($online as $key => $value_online) { ?>
                        <tr>
                            <td><?= $value_online['account_name'] ?></td>

            
                                <td align="right"><span class="label label-light-success">Online</span></td> <!-- login -->
                            
                        </tr>
                    <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-lg-4 col-xlg-3">
        <!-- Column -->
        <div class="card earning-widget">
            <div class="card-header">

                <h4 class="card-title m-b-0">List Staff (<b>Offline</b>)</h4>
            </div>
            <div class="card-block b-t collapse show">
                <table class="table v-middle no-border">
                    <tbody>
                    <?php foreach ($offline as $key => $value_offline) { ?>
                        <tr>
                            <td><?= $value_offline['account_name'] ?></td>

                                <td align="right"><span class="label label-light-danger">Offline</span></td> <!-- logout -->
                            
                        </tr>
                    <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>






</div>



        

