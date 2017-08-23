<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use app\models\Notification;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\helpers\Url;

HighchartsAsset::register($this)->withScripts(['modules/data', 'modules/drilldown']);

$this->title = 'Dashboard';

$notify = Notification::notify();

$script = <<< JS

$(document).ready(function(){

    $('#myModal').modal('hide');

    $('#pending').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('#overdue').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('#process').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('#approve').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

}); 

JS;
$this->registerJs($script);
?>

    <?php if (Yii::$app->user->identity->status_login > 1) { ?>


        <?php if(Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert"></button>
                 <?php echo  Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } else { ?>
        
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert"></button>
                 Another User Using Your Account
            </div>
        <?php } ?>


    <?php } else { ?>

    <?php } ?>
    

<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><span style="color: #ff0000;">NEW FEATURES</span> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <p>User Now Can 
                <a class="mytooltip" href="#">DELETE OR UPLOAD NEW FILE</a></p>
                <p> 1 . Click Menu Create Or Requisition Or Order</p>
                <p> 2 . Click Button File</p>
                <div class="embed-responsive embed-responsive-21by9">
                  <iframe class="embed-responsive-item" src="http://www.youtube.com/embed/cWMSC9m-LBQ"></iframe>
                </div>



            <br>
            <a href="http://www.youtube.com/watch?v=cWMSC9m-LBQ" target="_blank">View On YouTube</a>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="row m-t-40">
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-inverse card-danger">
            <div class="box text-center">

                <h1 class="font-light text-white">


                <?= Html::a(empty($sum_pending) ? '0' : $sum_pending,FALSE, ['value'=>Url::to(['site/list-notify','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'pending','style'=>'color:#fff;cursor:pointer;']) ?>


                </h1>
                <h6 class="text-white">Pending</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-warning card-inverse">
            <div class="box text-center">
                <h1 class="font-light text-white">

                    <?= Html::a(empty($sum_overdue) ? '0' : $sum_overdue,FALSE, ['value'=>Url::to(['site/list-overdue','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'overdue','style'=>'color:#fff;cursor:pointer;']) ?>

                </h1>
                <h6 class="text-white">Overdue</h6>
            </div>
        </div>
    </div>
    <!-- Column -->

    <?php if ($role == 'UserBuyer' || $role == 'User') { ?>

        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-info">
                <div class="box text-center">
                    <h1 class="font-light text-white">

<?= Html::a(empty($sum_process) ? '0' : $sum_process,FALSE, ['value'=>Url::to(['site/list-process-user','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'process','style'=>'color:#fff;cursor:pointer;']) ?>
</h1>
                    <h6 class="text-white">To Process</h6>
                </div>
            </div>
        </div>


    <?php } elseif ($role == 'Approval') { ?>

        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-info">
                <div class="box text-center">
                    <h1 class="font-light text-white">

                         <?= Html::a(empty($sum_process) ? '0' : $sum_process,FALSE, ['value'=>Url::to(['site/list-process','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'process','style'=>'color:#fff;cursor:pointer;']) ?>


                    </h1>
                    <h6 class="text-white">To Approve</h6>
                </div>
            </div>
        </div>

    <?php } elseif ($role == 'ApprovalBuyer' || $role == 'Buyer') { ?>

        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-info">
                <div class="box text-center">
                    <h1 class="font-light text-white">
<?= Html::a(empty($sum_process) ? '0' : $sum_process,FALSE, ['value'=>Url::to(['site/list-process-buyer','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'process','style'=>'color:#fff;cursor:pointer;']) ?>
                        
                    </h1>
                    <h6 class="text-white">To Process</h6>
                </div>
            </div>
        </div>


    

    <?php } ?>
   





    <!-- Column -->
    <div class="col-md-6 col-lg-3 col-xlg-3">
        <div class="card card-inverse card-dark">
            <div class="box text-center">
                <h1 class="font-light text-white">

                    <?= Html::a(empty($sum_approve) ? '0' : $sum_approve,FALSE, ['value'=>Url::to(['site/list-approve','user_id'=>Yii::$app->user->identity->id]),'class' => '','id'=>'approve','style'=>'color:#fff;cursor:pointer;']) ?>


                </h1>
                <h6 class="text-white">Approved</h6>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
      


<div class="row">
    <!-- Column -->
    <div class="col-lg-7 col-md-7">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div>
                                <h3 class="card-title">Total PO by Buyer</h3>
                                <h6 class="card-subtitle"></h6> </div>
    
                        </div>
                    </div>
                    <div class="col-12">
                        <div  >
                            
                                    <?php 


                                   if (empty($totalPO)) {

                                        $user_sales = $total_sales =0 ;
                                        $xAxis = $user_sales; 
                                        $yAxis = $total_sales;

                              
                                   } else {

                                      foreach ($totalPO as $key_project => $value_project) {
                                            $user_sales[] = $value_project['_id'];
                                            $total_sales[] = (int)$value_project['count'];

                                        }
                                   }

                                        $xAxis = $user_sales; 
                                        $yAxis = $total_sales;

                                    echo Highcharts::widget([
                                       'options' => [
                                          'title' => ['text' => 'Total Project By User'],
                                          'chart' => [
                                                'type' => 'column',
                                            ],
                                          'xAxis' => [
                                             'categories' => $xAxis
                                          ],
                                          'yAxis' => [
                                             'title' => ['text' => 'Total']
                                          ],
                                          'plotOptions' => [
                                            'column' => [
                                                'stacking' => 'normal',
                                                'dataLabels' => [
                                                    'enabled' => true,

                                                ],
                                            ],
                                          ],

                                          'series' => [
                                             [
                                              'name' => 'List User', 
                                              'colorByPoint' => true,
                                              'data' => $yAxis
                                             
                                             ],

                                          ]
                                       ]
                                    ]) ?>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-md-5">
        <div class="card">
            <div class="card-block">
                <h3 class="card-title">Total Amount PO Per Month : <b><?= date('F'); ?></b></h3>
                <h6 class="card-subtitle"></h6>
                <div>




                </div>
            </div>


        </div>
    </div>



</div>



<div class="row">

    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-block">
                <h3 class="card-title">Total Amount PO</h3>
                <h6 class="card-subtitle"></h6>
                <div>


                <table class="table">

                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Total PO</th>
                            <th>Total Amount (RM)</th>
                        </tr>
                    </thead>
                
                <?php 

                $sum_all_month = $total_without_gst =0;
                $i=0; foreach ($totalPOAll as $key_all_month => $value_all_month) { $i++;?>
                    <tr>
                        <td><?= $value_all_month['_id'];?></td>
                        <td><?= count($value_all_month['sellers']); ?></td>
                        <td>
                            <?php
                                $sum_all_month = $total_without_gst =0;


                                    foreach ($value_all_month['sellers'] as $key_b => $value_b) {
                                        foreach ($value_b as $key_v => $value_v) {
                                            foreach ($value_v as $key_a => $value_a) {
                                            
                                                $o =0;
                                                foreach ($value_a as $key_s => $value_s) {

                                                    $a = $value_s['cost'] * $value_s['quantity'];
                                                    $sum_all_month += $a;
                                                    //echo "<br>";
                                                    
                                                    
                                                }
                                                
                                                
                                            }

                                        }

                                    }
                                    ;
                                    echo "<p class='text-primary'>".$o = $sum_all_month."</p>";

                            ?>

                        </td>
                    </tr>

                <?php } ?>
                </table>





                </div>
            </div>


        </div>
    </div>


</div>



        

