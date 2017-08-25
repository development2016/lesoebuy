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
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
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


</div>



<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total PO Per Amount</h4>
                <h6 class="card-subtitle"></h6>
                <div class="table-responsive">


                <table class="table">

                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Total PO</th>
                            <th>Total Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            foreach ($totalPOAll as $key_all => $value_all) { ?>

                            <tr>
                                <td><?= $value_all['_id'][0]; ?></td>
                                <td><?= $value_all['count']; ?></td>
                                <td>
                                    <?php 
                                    $end_total = $total_all = 0;
                                            foreach ($value_all['itemsSold'] as $key_n => $value_n) {

                                                $total_all += $value_n['total_po'];
                                                
                                            
                                            }

                                    ?>

                                    <?php echo "<p class='text-primary'>".$end_total = $total_all."</p>";?>



                                </td>
                            </tr>

                        <?php } ?>

                        
                    </tbody>

                </table>






                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total Amount PO Per Month : <span style="color: #ff0000;"><?= date('F') ?></span></h4>
                <h6 class="card-subtitle"></h6>
                <div class="table-responsive">


                <table class="table">

                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Total PO</th>
                            <th>Total Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            foreach ($totalPOMonth as $key_all_month => $value_all_month) { ?>

                            <tr>
                                <td><?= $value_all_month['_id'][0]; ?></td>
                                <td><?= $value_all_month['count']; ?></td>
                                <td>
                                    <?php 
                                    $end_total_month = $total_all_month = 0;
                                            foreach ($value_all_month['itemsSold'] as $key_n_month => $value_n_month) {

                                                $total_all_month += $value_n_month['total_po'];
                                                
                                            
                                            }

                                    ?>

                                    <?php echo "<p class='text-primary'>".$end_total_month = $total_all_month."</p>";?>



                                </td>
                            </tr>

                        <?php } ?>

                        
                    </tbody>

                </table>

                



                </div>
            </div>
        </div>
    </div>



</div>



        

