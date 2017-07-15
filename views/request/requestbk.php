<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\LookupTitle;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\LookupModel;
use app\models\LookupBrand;


$this->title = strtoupper('Request');

$script = <<< JS
$(document).ready(function(){

     $('#request').DataTable();

}); 
JS;
$this->registerJs($script);


?>
<div class="row">
    <div class="col-lg-12">
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
      </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default" >
            <div class="panel-heading" style="background-color: #025b80;">
                <h3 class="panel-title" style="color: #fff;">
                  <span><?= Html::encode($this->title) ?></span>

                </h3>

            </div>
            <div class="panel-body">

                <ul class="nav nav-tabs" id="myTabs">
                  <li role="presentation" class="active"><a href="#start">Active</a></li>
                  <li role="presentation"><a href="#log">Log</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="start">
                      <br>


                            <table class="table"  id="request">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>PROJECT NO</th>
                                        <th>DETAILS</th>
                                        <th>INFORMATION</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; foreach ($model as $key => $value) { $i++; ?>
                       
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value['project_no']; ?></td>
                                        <td>
                                              <ul class="list-group">
                                                  <li class="list-group-item"><b>Title</b> : <?= $value['title']; ?></li>
                                                  <li class="list-group-item"><b>Description</b> : <?= $value['description']; ?></li>
                                                  <li class="list-group-item"><b>Due Date</b> : <?= $value['due_date']; ?></li>
                                                  <li class="list-group-item"><b>Date Create</b> : <?= $value['date_create']; ?></li>
                                              </ul>


                                        </td>
                                        <td>
                                            <table class="table table-bordered" >
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>Seller Name</th>
                                                        <th>Request From</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <tr>
                                                    <td><?php echo $value2['seller'] ?></td>
                                                    <td><?php echo $value['buyers'][0]['buyer']; ?></td>



                                                    <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                <!-- START  LEVEL -->
                                                                <?php if ($value2['approver'] == 'level') { ?>

                                                                        <td>
                                                                                <ul>
                                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                                            <li>
                                                                                            <?= $app['approval']; ?>

                                                                                            <?php if ($app['status'] == 'Waiting Approval') { ?>

                                                                                                : <label class="div-request-pulsate">Waiting To Approve</label>

                                                                                            <?php } elseif ($app['status'] == 'Approve') { ?>

                                                                                                : <label class="label bg-green-jungle font-dark"><?= $app['status']; ?></label>
                                                                                                
                                                                                            <?php } else { ?>

                                                                                            <?php } ?>



                                                                                            </li>
                                                                                            <br>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                        </td>




                                                                <?php } else { ?>

                                                                        <td>
                                                                            <?php if ($value2['status'] == 'Approve') { ?>
                                                                            
                                                                                <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                            <?php } else { ?>

                                                                                 <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                       </td>

                                                                <?php } ?>

                                                     <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                <!-- START  LEVEL -->
                                                                <?php if ($value2['approver'] == 'level') { ?>

                                                                        <td>
                                                                                <ul>
                                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                                            <li>
                                                                                            <?= $app['approval']; ?>

                                                                                            <?php if ($app['status'] == 'Waiting Approval') { ?>

                                                                                                : <label class="div-request-pulsate">Waiting To Approve</label>

                                                                                            <?php } elseif ($app['status'] == 'Approve') { ?>

                                                                                                : <label class="label bg-green-jungle font-dark"><?= $app['status']; ?></label>
                                                                                                
                                                                                            <?php } else { ?>

                                                                                            <?php } ?>



                                                                                            </li>
                                                                                            <br>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                        </td>




                                                                <?php } else { ?>

                                                                        <td>
                                                                            <?php if ($value2['status'] == 'Approve') { ?>
                                                                            
                                                                                <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                            <?php } else { ?>

                                                                                 <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                       </td>

                                                                <?php } ?>





                                                     <?php } ?>


                                                    <td>
                                                        <!-- START PR -->

                                                        <?php if ($value2['status'] == 'Approve') { ?>

                                                                <div class="btn-group">
                                                                  <button type="button" class="btn btn-default">Purchase Requisition</button>
                                                                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span class="caret"></span>
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                  </button>
                                                                  <ul class="dropdown-menu">
                                                                    <li>
                                                                      <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                            'project'=>(string)$value['_id'],
                                                                            'seller'=>$value2['seller'],
                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                            ],['target'=>'_blank']) ?>
                                                                    </li>
                                     
                                                                  </ul>
                                                                </div>



                                                        <?php } else { ?>

                
                                                                
                                                                    <?php if ($value2['status'] == 'Request Approval') { ?>


                                                                            <?php if ($value2['approver'] == 'level') { ?>

                                                                                    <?php if ($user->account_name == $value2['approver_level']) { ?>
                                                                                        
                                                                                        
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                                                                       
                                                                                    <?php } else { ?>

                                                                                    <?php } ?>

                                                                            <?php } else { ?>


                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                                                                        

                                                                            <?php } ?>


                                                                    <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                            <?php if ($value2['approver'] == 'level') { ?>

                                                                                    <?php if ($user->account_name == $value2['approver_level']) { ?>
                                                                                        
                                                                                        
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                                                                       
                                                                                    <?php } else { ?>

                                                                                    <?php } ?>

                                                                            <?php } else { ?>


                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                                                                        

                                                                            <?php } ?>




                                                                      
                                                                    <?php } ?>



                                                        <?php } ?>

                                                        <!-- END BUTTON PR -->


                                                        <!-- BUTTON QUOTATION  -->


                                                          

                                                                <div class="btn-group">
                                                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Details File
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                  
                              
                                                                    </ul>
                                                                </div>

                                                        

                                                

                                                        <!-- END BUTTON QUOTATION -->

                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                   
                                            </table>
                                            
                                        </td>
                                    </tr>
                                
                                   <?php } ?>
                                   </tbody>
                            </table>







                    </div>
                    <div class="tab-pane" id="log">
                     


                     
                    </div>


                    
                </div>
            </div>
      </div>
    </div>
</div>

