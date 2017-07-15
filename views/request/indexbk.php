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


$this->title = 'Request';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    $('#request').DataTable();


    $('.choose-approval').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.choose-approval-level').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });


    $('.choose-buyer').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });



}); 
JS;
$this->registerJs($script);


?>


<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default" >
        <div class="panel-heading" style="background-color: #025b80;overflow: hidden;">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;padding-bottom: 5px;color: #fff;"><?= Html::encode(strtoupper($this->title)) ?></h4>

        </div>
        <div class="panel-body">

            <ul class="nav nav-tabs" id="myTabs">
              <li role="presentation" class="active"><a href="#start">Active</a></li>
              <li role="presentation"><a href="#log">Log</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="start">
                  <br>

                            <table class="table"  id="request" >
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
                                                    <?php foreach ($value['sellers'] as $key => $value2) { ?>

                                                        <?php if ($value2['status'] == 'Approve') { ?>
                                                            <!-- this will check have second approval or not -->
                                                            <?php if (empty($value2['has_second_approval'])) { ?>

                                                                    <?php if ($value2['approver'] == 'level') { ?>

                                                                         <th>Approval / Status</th>
                                                                         <th>Status</th>
                                                                        
                                                                    <?php } else { ?>

                                                                        <th>Approval</th>
                                                                        <th>Status</th>

                                                                    <?php } ?>


                                                            <?php } ?>


                                                        <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                                <th>Request From</th>
                                                                <th>Buyer To Respond</th>
                                                                <th>Status</th>






                                                        <?php } else { ?>

                                                        <!-- START NORMAL  -->

                                                            <?php if ($value2['approver'] == 'level') { ?>

                                                                 <th>Approval / Status</th>
                                                                 <th>Status</th>
                                                                
                                                            <?php } else { ?>

                                                                <th>Approval</th>
                                                                <th>Status</th>

                                                            <?php } ?>

                                                        <!-- END NORMAL -->
                                                        <?php } ?>


                                                    <?php } ?>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <tr>
                                                    <td><?php echo $value2['seller'] ?></td>




                                                    <!-- ALL STATUS -->
                                                    <?php if ($value2['status'] == 'Approve') { ?>
                                                        <!-- this will check have second approval or not -->
                                                        <?php if (empty($value2['has_second_approval'])) { ?>

                                                                <td>
                                                                    <ul>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <li><?= $app['approval']; ?></li>
                                                                        <br>
                                                                    <?php } ?>
                                                                    </ul>

                                                                </td>
                                                                <td>

                                                                        <?php if ($value2['status'] == 'Approve') { ?>
                                                                        
                                                                            <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                        <?php } else { ?>


                                                                            <?php if (!empty($value2['PO_process_by'])) { ?>

                                                                                <?php if ($user->account_name == $value2['PO_process_by']) { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                   
                                                                                <?php } else { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                    <br>
                                                                                    Process By : <span class="label bg-yellow-saffron font-dark"><?php echo $value2['PO_process_by']; ?></span>


                                                                                <?php } ?>
                                                                                
                                                                            <?php } else { ?>

                                                                                <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                             
         
                                                                        <?php } ?>

                                                                </td>



                                                        <?php } else { ?>

                                                                <td>
                                                                    <ul>
                                                                    <?php foreach ($value2['approval_next'] as $key_next => $app_next) { ?>
                                                                        <li><?= $app_next['approval_next']; ?></li>
                                                                        <br>
                                                                    <?php } ?>
                                                                    </ul>

                                                                </td>
                                                                <td>

                                                                        <?php if ($value2['status'] == 'Approve') { ?>
                                                                        
                                                                            <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                        <?php } else { ?>


                                                                            <?php if (!empty($value2['PO_process_by'])) { ?>

                                                                                <?php if ($user->account_name == $value2['PO_process_by']) { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                   
                                                                                <?php } else { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                    <br>
                                                                                    Process By : <span class="label bg-yellow-saffron font-dark"><?php echo $value2['PO_process_by']; ?></span>


                                                                                <?php } ?>
                                                                                
                                                                            <?php } else { ?>

                                                                                <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                             
         
                                                                        <?php } ?>

                                                                </td>



                                                        <?php } ?>





                                                    <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                        <td><?php echo $value['requester'] ?></td>
                                                        <td>
                                                            <ul>
                                                                <?php foreach ($value['buyers'] as $key4 => $value4) { ?>
                                                                    <li><?php echo $value4['buyer'] ?></li>
                                                                    <br>
                                                                <?php } ?>
                                                                
                                                            </ul>
                                                            
                                                        </td>
                                                        <td><?php echo $value2['status'] ?></td>

              


                                                    <?php } else { ?>

                                                        <!-- START APPROVAL -->

                                                            <?php if ($value2['approver'] == 'level') { ?>

                                                                <td>
                                                                    <ul>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <li>
                                                                        <?= $app['approval']; ?>

                                                                        <?php if ($app['status'] == 'Waiting Approval') { ?>

                                                                            : <label class="div-request-pulsate"><?= $app['status']; ?></label>

                                                                        <?php } elseif ($app['status'] == 'Approve') { ?>

                                                                            : <label class="label bg-green-jungle font-dark"><?= $app['status']; ?></label>
                                                                            
                                                                        <?php } else { ?>



                                                                        <?php } ?>

                                                                            
                                                                        </li>
                                                                        <br>
                                                                    <?php } ?>
                                                                    </ul>

                                                                </td>
                                                                <td>

                                                                        <?php if ($value2['status'] == 'Approve') { ?>
                                                                        
                                                                            <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                        <?php } else { ?>


                                                                            <?php if (!empty($value2['PO_process_by'])) { ?>

                                                                                <?php if ($user->account_name == $value2['PO_process_by']) { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                   
                                                                                <?php } else { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                    <br>
                                                                                    Process By : <span class="label bg-yellow-saffron font-dark"><?php echo $value2['PO_process_by']; ?></span>


                                                                                <?php } ?>
                                                                                
                                                                            <?php } else { ?>

                                                                                <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                             
         
                                                                        <?php } ?>

                                                                </td>




                                                            <?php } else { ?>

                                                                <td>
                                                                    <ul>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <li><?= $app['approval']; ?></li>
                                                                        <br>
                                                                    <?php } ?>
                                                                    </ul>

                                                                </td>
                                                                <td>

                                                                        <?php if ($value2['status'] == 'Approve') { ?>
                                                                        
                                                                            <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                        <?php } else { ?>


                                                                            <?php if (!empty($value2['PO_process_by'])) { ?>

                                                                                <?php if ($user->account_name == $value2['PO_process_by']) { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                   
                                                                                <?php } else { ?>

                                                                                    <?php echo $value2['status']; ?>
                                                                                    <br>
                                                                                    Process By : <span class="label bg-yellow-saffron font-dark"><?php echo $value2['PO_process_by']; ?></span>


                                                                                <?php } ?>
                                                                                
                                                                            <?php } else { ?>

                                                                                <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                             
         
                                                                        <?php } ?>

                                                                </td>

                                                            <?php } ?>

                                                            <!-- END FIRST APPROVAL --> 


                                                    <?php } ?>
                                                    <!-- END ALL STATUS -->

                                                    <td>    
                                                        <!-- ACTION BUTTON -->

                                                        <?php if ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                            <div class="btn-group">
                                                              <button type="button" class="btn btn-default"> Submit To Approver</button>
                                                              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="caret"></span>
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                              </button>
                                                              <ul class="dropdown-menu">
                                                                <li>
                                                                    <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                    'request/choose-approval',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$user->account_name,
                                                                    'type' => 'direct',
                             
                                                                    ]),'class' => 'choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                </li>
                                                                <li>
                                                                    <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                'request/choose-approval-level',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$user->account_name,
                                                                                'type' => 'direct',
                                         
                                                                                ]),'class' => 'choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                </li>
                               
                                                              </ul>
                                                            </div>


                                                                <?= Html::a('Purchase Requisition', ['html/direct-purchase-requisition-html',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                'approver' => $value2['approver'],
                                                                ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>



                                                        <?php } elseif ($value2['status'] == 'Reject PR') { ?>


                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                'approver'=>$value2['approver'],
                                                                ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                                       


                                                        <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                  
                                                                <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value2['PO_process_by'],
                                                                ],['class'=>'btn btn-default','title'=>'Purchase Order']) ?>
                                                      
                                                               <div class="btn-group">
                                                                  <button type="button" class="btn btn-default"> Purchase Requistion</button>
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
                                    

            
                                                                <!-- BUTTON PR AFTER APPROVE -->
                                                        <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                            <?php if ($info_role == 'Found') { ?>

                                                                <?php if ($value2['temp_status'] == 'Change Buyer') { ?>

                                                                        <div class="btn-group">
                                                                          <button type="button" class="btn btn-default"> Submit To Approver</button>
                                                                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <span class="caret"></span>
                                                                            <span class="sr-only">Toggle Dropdown</span>
                                                                          </button>
                                                                          <ul class="dropdown-menu">
                                                                            <li>
                                                                                <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                                'request/choose-approval',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$user->account_name,
                                                                                'type' => 'direct',
                                         
                                                                                ]),'class' => 'choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                            </li>
                                                                            <li>
                                                                                <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                            'request/choose-approval-level',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$user->account_name,
                                                                                            'type' => 'direct',
                                                     
                                                                                            ]),'class' => 'choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                            </li>
                                           
                                                                          </ul>
                                                                        </div>



                                                                <?php } else { ?>

                                                                    <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-default','title'=>'Purchase Order']) ?>




                                                                <?php } ?>







                                                                <?php if ($info_role_2 == 'Found') { ?>

                                                                    <?php if ($value2['temp_status'] == 'Change Buyer') { ?>


                                                                    <?php } else { ?>

                                                                        <?php if (empty($value2['approver'])) { ?>
                                                                            
                                                                            <?= Html::a('Submit To Buyer',FALSE, ['value'=>Url::to([
                                                                            'request/choose-buyer',
                                                                            'project'=>(string)$value['_id'],
                                                                            'seller'=>$value2['seller'],
                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                            'role'=>'buyer'
                                                           
                                     
                                                                            ]),'class' => 'btn btn-default choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition']) ?>


                                                                            
                                                                        <?php } else { ?>

                                                                        <?php } ?>





                                                                    <?php } ?>


                                                                    




                                                                <?php } ?>



                                                            <?php } else { ?>

                                                                <?= Html::a('Choose Buyer',FALSE, ['value'=>Url::to([
                                                                    'request/choose-buyer',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'role'=>'user'
                                                   
                             
                                                                    ]),'class' => 'btn btn-default choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition']) ?>



                                                                <!-- here if role user found -->

                                                            <?php } ?>





                                                                


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



                                                            <!-- BUTTON  PR AFTER APPROVE --> 
                                                        <?php } else { ?>

                                                            <!-- BUTTON PR BEFORE APPROVE -->

                   
                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                'approver'=>$value2['approver'],
                                                                ],['class'=>'btn btn-default','title'=>'Purchase Requisition']) ?>
                                              

                                                            <!-- END BUTTON PR BEFORE APPROVE -->

                                                        <?php } ?>

                                                            <!-- BUTTON QUOTATION -->

                                                            <div class="btn-group">
                                                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Details File
                                                                    <i class="fa fa-angle-down"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                              
                          
                                                                </ul>
                                                            </div>


                                                            <!-- END BUTTON QUOTATION -->

                                                        <!-- END ALL ACTION BUTTON -->
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
                <div class="tab-pane active" id="log">
                  <br>


                </div>


            </div>
        </div>
    </div>
    </div>
</div>