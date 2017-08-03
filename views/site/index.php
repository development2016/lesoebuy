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

                <h4 class="card-title m-b-0">List Staff</h4>
            </div>
            <div class="card-block b-t collapse show">
                <table class="table v-middle no-border">
                    <tbody>
                    <?php foreach ($user as $key => $value) { ?>
                        <tr>
                            <td><?= $value['account_name'] ?></td>

                            <?php if ($value['status_login'] == 1) { ?>
                                <td align="right"><span class="label label-light-success">Online</span></td> <!-- login -->
                                
                            <?php } elseif ($value['status_login'] == 2) { ?>
                                <td align="right"><span class="label label-light-warning">Away</span></td> <!-- afk -->

                            <?php } elseif ($value['status_login'] == 3) { ?>
                                <td align="right"><span class="label label-light-primary">Idle</span></td> <!-- not active in chat -->

                            <?php } elseif ($value['status_login'] == 4) { ?>
                                <td align="right"><span class="label label-light-info">Busy</span></td> <!-- change status manual in chat -->

                            <?php } elseif ($value['status_login'] == 0) { ?>
                                <td align="right"><span class="label label-light-danger">Offline</span></td> <!-- logout -->
                     
                            <?php } ?>
                            
                        </tr>
                    <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>



        

