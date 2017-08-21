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
     $('#log-history').DataTable();

}); 
JS;
$this->registerJs($script);


?>


<div class="row">
  <div class="col-md-12">
      <div class="card">
        <div class="card-block">

            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            <h6 class="card-subtitle">Description About Panel</h6> 

        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item"> 
              <a class="nav-link active" data-toggle="tab" href="#active" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Active</span></a> 
            </li>
            <li class="nav-item"> 
              <a class="nav-link" data-toggle="tab" href="#log" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Log</span></a> 
            </li>

        </ul>
              <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="active" role="tabpanel">
                <div class="p-20 ">
                  <div class="table-responsive m-t-40">
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
                                                  <li class="list-group-item"><b>PR No</b> : 

                                                    <a href="#" class="mytooltip" ><?= $value['sellers'][0]['purchase_requisition_no']; ?></a>

                                                  </li>
                                                  
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
                                                        <!-- START CHECK ROLE -->
                                                        <?php if ($value['request_role'] == 'Buyer') { ?>
                                                        <!-- ROLE BUYER -->
                                                                <!-- START APPROVAL -->
                                                            <?php if ($value2['approver'] == 'normal') { ?>
                                                                <!-- REQUEST APPROVAL -->
                                                                <?php if ($value2['status'] == 'Request Approval') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                  <?= Html::a('File', ['file/index',
                                                                  'project'=>(string)$value['_id'],
                                                                  ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                      'approver'=>$value2['approver'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } ?>


                                                            <?php } elseif ($value2['approver'] == 'level') { ?>

                                                                <?php if ($value2['status'] == 'Request Approval') { ?>


                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>

                                                                  </td>
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>

                                                                      <?php if ($app['approval'] == $user->account_name) { ?>

                                                                          <?php if ($app['status'] == 'Approve') { ?>

                                                                          <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                              'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver'=>$value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>



                                                                          <?= Html::a('File', ['file/index',
                                                                          'project'=>(string)$value['_id'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                              <?php } elseif ($app['status'] == 'Waiting Approval') { ?>

                                                                                  <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                      'project'=>(string)$value['_id'],
                                                                                      'seller'=>$value2['seller'],
                                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                                      'approver' => $value2['approver'],
                                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                                <?= Html::a('File', ['file/index',
                                                                                'project'=>(string)$value['_id'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>



                                                                            
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                'approver' => $value2['approver'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                            <?= Html::a('File', ['file/index',
                                                                            'project'=>(string)$value['_id'],
                                                                            ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                          <?php } ?>
                                                                          
                                                                      <?php } ?>

                                                                    <?php } ?>

                                                                  </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                    <td>
                                                                        <?php echo $value2['status']; ?>
                                                                        <br>
                                                                        <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                        <br>
                                                                      <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                      'approver'=>$value2['approver'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } ?>



                                                                  
                                                            <?php } ?>
                                                            <!-- END  -->
                                                            
                                                        <?php } elseif ($value['request_role'] == 'User') { ?>

                                                            <?php if ($value2['approver'] == 'normal') { ?>
                                                                <!-- REQUEST APPROVAL -->
                                                                <?php if ($value2['status'] == 'Request Approval') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                      'approver'=>$value2['approver'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>


                                                                <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>





                                                                <?php } ?>

                                                            <?php } elseif ($value2['approver'] == 'level') { ?>


                                                                <?php if ($value2['status'] == 'Request Approval') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>

                                                                  </td>
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>

                                                                      <?php if ($app['approval'] == $user->account_name) { ?>

                                                                          <?php if ($app['status'] == 'Approve') { ?>

                                                                          <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                              'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver'=>$value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                              <?php } elseif ($app['status'] == 'Waiting Approval') { ?>

                                                                                  <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                      'project'=>(string)$value['_id'],
                                                                                      'seller'=>$value2['seller'],
                                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                                      'approver' => $value2['approver'],
                                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                                <?= Html::a('File', ['file/index',
                                                                                'project'=>(string)$value['_id'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>



                                                                            
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                'approver' => $value2['approver'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                          <?php } ?>
                                                                          
                                                                      <?php } ?>

                                                                    <?php } ?>

                                                                  </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                    <td>
                                                                        <?php echo $value2['status']; ?>
                                                                        <br>
                                                                        <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                        <br>
                                                                      <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                      'approver'=>$value2['approver'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

              

                                                                <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                    <td>
                                                                      <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                        <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                        <br>
                                                                      <?php } ?>

                                                                    </td>
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>

                                                                      <?php if ($app['approval'] == $user->account_name) { ?>

                                                                          <?php if ($app['status'] == 'Approve') { ?>

                                                                          <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                              'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver'=>$value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>



                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>
                                                                                                                                                    
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                          <?php } ?>
                                                                          
                                                                      <?php } ?>

                                                                    <?php } ?>

                                                                  </td>




                                                                <?php } ?>

                                                                  
                                                            <?php } ?>
                                                            <!-- END  -->

                                                        <?php } elseif ($value['request_role'] == 'BuyerUser') { ?>

                                                            <?php if ($value2['approver'] == 'normal') { ?>
                                                                <!-- REQUEST APPROVAL -->
                                                                <?php if ($value2['status'] == 'Request Approval') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                      'approver'=>$value2['approver'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>

                                                                <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                    <td>
                                                                      <?php echo $value2['status']; ?>
                                                                    </td>
                                                                    <td>
                                                                      
                                                                      <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                       <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    </td>
                                                                    


                                                                <?php } ?>

                                                            <?php } elseif ($value2['approver'] == 'level') { ?>


                                                                <?php if ($value2['status'] == 'Request Approval') { ?>

                                                                      <td>
                                                                        <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                          <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                          <br>
                                                                        <?php } ?>

                                                                      </td>
                                                                      <td>
                                                                        <?php foreach ($value2['approval'] as $key => $app) { ?>

                                                                          <?php if ($app['approval'] == $user->account_name) { ?>

                                                                              <?php if ($app['status'] == 'Approve') { ?>

                                                                              <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                                  'project'=>(string)$value['_id'],
                                                                              'seller'=>$value2['seller'],
                                                                              'buyer'=>$value['buyers'][0]['buyer'],
                                                                              'approver'=>$value2['approver'],
                                                                              ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>


                                                                              <?= Html::a('File', ['file/index',
                                                                              'project'=>(string)$value['_id'],
                                                                              ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                              <?php } elseif ($app['status'] == 'Waiting Approval') { ?>

                                                                                  <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                      'project'=>(string)$value['_id'],
                                                                                      'seller'=>$value2['seller'],
                                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                                      'approver' => $value2['approver'],
                                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                                <?= Html::a('File', ['file/index',
                                                                                'project'=>(string)$value['_id'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>
                                                                            
                                                                                
                                                                              <?php } else { ?>

                                                                                  <?= Html::a('xxPurchase Requisition', ['request/direct-purchase-requisition',
                                                                                      'project'=>(string)$value['_id'],
                                                                                      'seller'=>$value2['seller'],
                                                                                      'buyer'=>$value['buyers'][0]['buyer'],
                                                                                      'approver' => $value2['approver'],
                                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                                <?= Html::a('File', ['file/index',
                                                                                'project'=>(string)$value['_id'],
                                                                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                              <?php } ?>
                                                                              
                                                                          <?php } ?>

                                                                        <?php } ?>

                                                                      </td>

                                                                <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td><?php echo $value2['status']; ?></td>
                                                                  <td>
                                                                        <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>


                                                                <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>

                                                                  </td>
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>

                                                                      <?php if ($app['approval'] == $user->account_name) { ?>

                                                                          <?php if ($app['status'] == 'Approve') { ?>

                                                                          <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition',
                                                                              'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver'=>$value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>



                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>
                                                                                                                                                      
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                          <?php } ?>
                                                                          
                                                                      <?php } ?>

                                                                    <?php } ?>

                                                                  </td>



                                                                      

                                                                  <?php } ?>





                                                                  
                                                            <?php } ?>




                                                        


                                                        <?php } ?>



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

                </div>
            </div>
        <div class="tab-pane" id="log" role="tabpanel">
            <div class="p-20 ">
              <div class="table-responsive m-t-40">

                    <table class="table" id="log-history">
                      <thead >
                        <tr>
                            <th>No</th>
                            <th>Project No</th>
                            <th>Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=0; foreach ($log as $key_log => $value_log) { $i++;?>
                        <tr>
                          <td><?= $i; ?></td>
                          <td><?= $value_log['_id']; ?></td>
                          <td>
                              <?php foreach ($value_log['info'] as $key_info => $value_info) { ?>
                              <table class="table table-bordered" >
                                  <thead class="thead-default">
                                    <tr>
                                        <th>Seller Name</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Request From</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      
                                        <td><?= $value_info['seller'] ?></td>
                                        <td>
                                          <?php if ($value_info['status'] == 'Reject PR') { ?>

                                              <span class="label label-warning"><?= $value_info['status'] ?></span>
  
                                          <?php } elseif ($value_info['status'] == 'Approve') { ?>

                                              <span class="label" style="background-color: #2eb300;"><?= $value_info['status'] ?></span>
                                              
                                          <?php } ?>
                                        </td>
                                        <td>
                                          <?php if ($value_info['status'] == 'Reject PR') { ?>
                                              <span class="label label-inverse"><?= $value_info['date_reject'] ?></span>

                                          <?php } elseif ($value_info['status'] == 'Approve') { ?>
                                              <span class="label label-inverse"><?= $value_info['date_approve'] ?></span>

                                          <?php } ?>
                                        </td>
                                        <td><?= $value_info['project']['0']['buyers'][0]['buyer']; ?></td>
                                        <td>

                                              <div class="btn-group">
                                                  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      Purchase Requisition
                                                  </button>
                                                  <div class="dropdown-menu animated flipInX">
                                                        <?= Html::a('<b>'.$value_info['purchase_requisition_no'].'</b>', [
                                                              'html/direct-purchase-requisition-html-inactive',
                                                              'log_id' => (string)$value_info['log_id'],
                                                              'buyer' => $value_info['by'],
                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>

                                                      
                                                  </div>
                                              </div>
                                  



                                            <?= Html::a('File', ['file/index',
                                                'project'=>(string)$value_info['project'][0]['_id'],
                                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                        </td>

                                        
                                    </tr>

                                  </tbody>
                              </table>
                              <?php }?>

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
    </div>
</div>