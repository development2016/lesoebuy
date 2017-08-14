<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use app\models\Notification;
use miloschuman\highcharts\Highcharts;

$this->title = 'Dashboard';

$notify = Notification::notify();

$script = <<< JS

$(document).ready(function(){

        $('#myModal').modal('show');


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

                <p>User Now Can <a class="mytooltip" href="#">CHANGE APPROVER</a> On <b style="color: #ff0000;">(Create PR / Reject PR)</b></p>
                <p style="font-size: 13px;color: #ff0000;">* Once User Submit For Approval, Approver Can`t Be Change Unless Approval Reject The PR</p>
                <p>
                    <img src="<?php echo Yii::$app->request->baseUrl; ?>/image/leso/feature-2.gif" class="card-img-top img-responsive" />
                </p>

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
    <!-- Column -->
    <div class="col-lg-8 col-md-7">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div>
                                <h3 class="card-title">Total Project</h3>
                                <h6 class="card-subtitle"></h6> </div>
    
                        </div>
                    </div>
                    <div class="col-12">
                        <div  >
                            
                                    <?php 


                                   if (empty($totalProject)) {

                                        $user_sales = $total_sales =0 ;
                                        $xAxis = $user_sales; 
                                        $yAxis = $total_sales;

                              
                                   } else {

                                      foreach ($totalProject as $key_project => $value_project) {
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

    <div class="col-lg-4 col-md-5">
        <div class="card">
            <div class="card-block">
                <h3 class="card-title">Total PO </h3>
                <h6 class="card-subtitle"></h6>
                <div >
                          <?php if (empty($totalPO)) {
                                      
                                } else {


                                    foreach ($totalPO as $key_po => $value_po) {

                                             $data[] = [$value_po['_id'],(int)$value_po['count']];
                                        

                                    }
                                
                                    echo Highcharts::widget([
                                       'options' => [
                                            'title' => ['text' => 'Total PO By User'],
                                            'chart' => [
                                                'plotBackgroundColor'=> null,
                                                'plotBorderWidth'=> 0,
                                                'plotShadow'=> false

                                            ],
                                            'tooltip' => [
                                                'pointFormat' => '{series.name} : <b>{point.y}</b>'
                                            ],

                                            'plotOptions' => [
                                                'pie' => [
                                                    'dataLabels' => [
                                                        'enabled' => true,
                                                    
                                                        'style' => [
                                                            'fontWeight' => 'bold',
                                                            'color' => 'black'
                                                        ]
                                                    ],
                                   
                                                ],
                                            ],
                                          'series' => [
                                             [
                                                'type' => 'pie',
                                                'name' => 'PO',
                                                'innerSize' => '50%',
                                                'data' => $data
                                                    
                                                
 
                                             
                                             ],

                                          ]

                                       ]
                                    ]);





                                  }
 
                                  ?>


                </div>
            </div>


        </div>
    </div>



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

    <div class="col-lg-4 col-xlg-3">
        <!-- Column -->
        <div class="card earning-widget">
            <div class="card-header">

                <h4 class="card-title m-b-0">List Staff (<b>Idle</b>)</h4>
            </div>
            <div class="card-block b-t collapse show">
                <table class="table v-middle no-border">
                    <tbody>
                    <?php foreach ($idle as $key => $value_idle) { ?>
                        <tr>
                            <td><?= $value_idle['account_name'] ?></td>

                                <td align="right"><span class="label label-light-primary">Idle</span></td>
                                 <!-- Idle -->
                            
                        </tr>
                    <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>




</div>



        

