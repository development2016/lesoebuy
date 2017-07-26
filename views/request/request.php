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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                  <?= Html::a('File', ['file/index',
                                                                  'project'=>(string)$value['_id'],
                                                                  ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                      ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>



                                                                          <?= Html::a('File', ['file/index',
                                                                          'project'=>(string)$value['_id'],
                                                                          ],['class'=>'btn btn-primary','title'=>'File']) ?>
                                                                            
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                'approver' => $value2['approver'],
                                                                                ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                            <?= Html::a('File', ['file/index',
                                                                            'project'=>(string)$value['_id'],
                                                                            ],['class'=>'btn btn-primary','title'=>'File']) ?>

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
                                                                      ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                      ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>
                                                                            
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                'approver' => $value2['approver'],
                                                                                ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>

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
                                                                      ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                      <?= Html::a('File', ['file/index',
                                                                      'project'=>(string)$value['_id'],
                                                                      ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>



                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>
                                                                                                                                                    
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>

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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                      ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                File
                                                                            </button>
                                                                            <div class="dropdown-menu animated flipInX">
                                                                                
                                                                                
                                                                                
                                                                            </div>
                                                                        </div>


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
                                                                              ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>



                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        File
                                                                                    </button>
                                                                                    <div class="dropdown-menu animated flipInX">
                                                                                        
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                              <?php } else { ?>

                                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                    'project'=>(string)$value['_id'],
                                                                                    'seller'=>$value2['seller'],
                                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                                    'approver' => $value2['approver'],
                                                                                    ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>

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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>


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
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>



                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>
                                                                                                                                                      
                                                                          <?php } else { ?>

                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                          'project'=>(string)$value['_id'],
                                                                          'seller'=>$value2['seller'],
                                                                          'buyer'=>$value['buyers'][0]['buyer'],
                                                                          'approver' => $value2['approver'],
                                                                          ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-primary','title'=>'File']) ?>

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
                            <th>Information</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i=0; foreach ($log as $key_log => $value_log) { $i++;?>
                        <tr>
                          <td><?= $i; ?></td>
                          <td><?= $value_log[0][0]['project_no']; ?></td>
                          <td>
                              <ul class="list-group">
                                  <li class="list-group-item"><b>Title</b> : <?= $value_log[0][0]['title']; ?></li>
                                  <li class="list-group-item"><b>Description</b> : <?= $value_log[0][0]['description']; ?></li>
                                  <li class="list-group-item"><b>Due Date</b> : <?= $value_log[0][0]['due_date']; ?></li>
                                  <li class="list-group-item"><b>Date Create</b> : <?= $value_log[0][0]['date_create']; ?></li>

                              </ul>

                          </td>
                          <td>
                              <table class="table table-bordered" >
                                  <thead class="thead-default">
                                    <tr>
                                        <th>Seller Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <td>
                                          <?php if (empty($value_log[0][0]['sellers']['seller'])) {
                                            
                                          } else {

                                            echo $value_log[0][0]['sellers']['seller'];

                                          }?>
                                          
                                        </td>
                                        <td>
                                            <?php echo $value_log[0][0]['sellers']['status']; ?>
                                        </td>
                                        <td>

                                              <div class="btn-group">
                                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      Purchase Requisition
                                                  </button>
                                                  <div class="dropdown-menu animated flipInX">
                                                      
                                                        <?= Html::a('<b>'.$value_log[0][0]['sellers']['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                              'project'=>(string)$value_log[0][0]['_id'],
                                                              'seller'=>$value_log[0][0]['sellers']['seller'],
                                                             'buyer'=>$value_log[0][0]['buyers'][0]['buyer'],
                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                      
                                                  </div>
                                              </div>
                                  



                                            <div class="btn-group">
                                                <?= Html::a('File', ['file/index'],['class'=>'btn btn-primary']) ?>
                                            </div>


                                        </td>

                                        
                                    </tr>

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




        </div>




      </div>
    </div>
</div>