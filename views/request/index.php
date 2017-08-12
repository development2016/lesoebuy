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


$this->title = 'Requisition';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    $('#request').DataTable();
    $('#log-history').DataTable();


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

    $('#cancelprbybuyer').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });




}); 
JS;
$this->registerJs($script);


?>

<?php if(Yii::$app->session->hasFlash('reject')) { ?>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert"></button>
         <?php echo  Yii::$app->session->getFlash('reject'); ?>
    </div>
<?php } ?>

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
                            <table class="table"  id="request" >
                                <thead>
                                    <tr>
                                      <th>No</th>
                                      <th>Project No</th>
                                      <th>Details</th>
                                      <th>Information</th>
                                                  
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
                                                      <th>Approval</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                  <tr>
                                                    <td><?php echo $value2['seller'] ?></td>

                                                    <!-- START CHECK ROLE -->
                                                    <?php if ($value['request_role'] == 'Buyer') { ?>
                                                      <!-- ROLE BUYER -->
                                                            <!-- START APPROVAL -->
                                                          <?php if ($value2['approver'] == 'normal') { ?>

                                                              <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
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

                                                              <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                              <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>




                                                                  </td>


                                                              <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>


                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>




                                                                  </td>


                                                              <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>


                                                              <?php } ?>
                                                              <!-- end status -->
                                                              

                                                          <?php } elseif ($value2['approver'] == 'level') { ?>

                                                              <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
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

                                                              <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td><?php echo $value2['status']; ?></td>
                                                                  <td>
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>
                                                                  </td>



                                                              <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                  <div class="btn-group">
                                                                      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                          Purchase Requisition
                                                                      </button>
                                                                      <div class="dropdown-menu animated flipInX">
                                                                          <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                          
                                                                          
                                                                      </div>
                                                                  </div>

        
                                                                  <?= Html::a('File', ['file/index',
                                                                  'project'=>(string)$value['_id'],
                                                                  ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                              <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                  <div class="btn-group">
                                                                      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                          Purchase Requisition
                                                                      </button>
                                                                      <div class="dropdown-menu animated flipInX">
                                                                          <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                          
                                                                          
                                                                      </div>
                                                                  </div>

                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                              <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>



                                                                  

                                                              <?php } ?>


                                                          <?php } ?>
                                                          <!-- END level -->

                                                          
                                                    <?php } elseif ($value['request_role'] == 'User') { ?>
                                                      <!-- ROLE USER -->

                                                        <?php if ($value2['approver'] == 'normal') { ?>

                                                            <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
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

                                                            <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Choose Buyer',FALSE, ['value'=>Url::to([
                                                                    'request/choose-buyer',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'role'=>'user'
                                                   
                             
                                                                    ]),'class' => 'btn btn-secondary choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition','style'=>'color : #292b2c']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>


                                                            <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      PR Approved By : <b><?= $app['approval']; ?></b>
                                                                      <br>
                                                                      Request From : <b><?= $value['requester']; ?></b>
                                                                    <?php } ?>
                                                                       
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Submit To Approver
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                              'request/choose-approval',
                                                                              'project'=>(string)$value['_id'],
                                                                              'seller'=>$value2['seller'],
                                                                              'buyer'=>$user->account_name,
                                                                              'type' => 'direct',
                                       
                                                                              ]),'class' => 'dropdown-item choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>
                                                                              <div class="dropdown-divider"></div>
                                                                              <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                  'request/choose-approval-level',
                                                                                  'project'=>(string)$value['_id'],
                                                                                  'seller'=>$value2['seller'],
                                                                                  'buyer'=>$user->account_name,
                                                                                  'type' => 'direct',
                                           
                                                                                  ]),'class' => 'dropdown-item choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                    <?= Html::a('REJECT',FALSE, ['value'=>Url::to([
                                                                          'information/reject-by-buyer',
                                                                          'seller'=>$value2['seller'],
                                                                          'project'=>(string)$value['_id'],
                                                                          'buyer'=>$user->account_name,
                                                      
                                                                          ]),'class' => 'btn btn-warning','id'=>'cancelprbybuyer','style'=>'color:#fff;']) ?>




                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Process') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-check',
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
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-check',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Reject PR Next') { ?>
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit-next',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>





                                                            <?php } elseif ($value2['status'] == 'Approve Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                  <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>


                                                              <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>




                                                          <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Purchase Requisition
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                              'project'=>(string)$value['_id'],
                                                                                              'seller'=>$value2['seller'],
                                                                                             'buyer'=>$value['buyers'][0]['buyer'],
                                                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                              
                                                                              
                                                                          </div>
                                                                      </div>

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

                                                            <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>




                                                            <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Choose Buyer',FALSE, ['value'=>Url::to([
                                                                    'request/choose-buyer',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'role'=>'user'
                                                   
                             
                                                                    ]),'class' => 'btn btn-secondary choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition','style'=>'color : #292b2c']) ?>

                                                                    

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                                  <td>
                                                                    PR Approved By : 
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <b><?= $app['approval']; ?><br></b>
                                                                
                                                                    <?php } ?>
                                                                    Request From : <b><?= $value['requester']; ?></b>
                                                                       
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Submit To Approver
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                              'request/choose-approval',
                                                                              'project'=>(string)$value['_id'],
                                                                              'seller'=>$value2['seller'],
                                                                              'buyer'=>$user->account_name,
                                                                              'type' => 'direct',
                                       
                                                                              ]),'class' => 'dropdown-item choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>
                                                                            <div class="dropdown-divider"></div>
                                                                            <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                          'request/choose-approval-level',
                                                                                          'project'=>(string)$value['_id'],
                                                                                          'seller'=>$value2['seller'],
                                                                                          'buyer'=>$user->account_name,
                                                                                          'type' => 'direct',
                                                   
                                                                                          ]),'class' => 'dropdown-item choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                        </div>
                                                                    </div>


                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>



                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                    <?= Html::a('REJECT',FALSE, ['value'=>Url::to([
                                                                          'information/reject-by-buyer',
                                                                          'seller'=>$value2['seller'],
                                                                          'project'=>(string)$value['_id'],
                                                                          'buyer'=>$user->account_name,
                                                      
                                                                          ]),'class' => 'btn btn-warning','id'=>'cancelprbybuyer','style'=>'color:#fff;']) ?>




                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Process') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                    
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-check',
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

                                                            <?php } elseif ($value2['status'] == 'Approve Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                  <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>


                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Purchase Requisition
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                              'project'=>(string)$value['_id'],
                                                                                              'seller'=>$value2['seller'],
                                                                                             'buyer'=>$value['buyers'][0]['buyer'],
                                                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                              
                                                                              
                                                                          </div>
                                                                      </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>


                                                            <?php } elseif ($value2['status'] == 'Reject PR Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit-next',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                          <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>



                                                            <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            File
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                  </td>

                                                              <?php } ?>
                                                                
                                                        <?php } ?>




                                                    <?php } elseif ($value['request_role'] == 'BuyerUser') { ?>
                                                    <!-- ROLE USER/BUYER -->
                                                        <?php if ($value2['approver'] == 'normal') { ?>

                                                            <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                
                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
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


                                                            <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>



                                                            <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>



                                                                    <?= Html::a('Choose Buyer',FALSE, ['value'=>Url::to([
                                                                    'request/choose-buyer',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'role'=>'buyer'
                                                   
                             
                                                                    ]),'class' => 'btn btn-secondary choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition','style'=>'color : #292b2c']) ?>


                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                          <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                              <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>





                                                            <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      PR Approved By : <b><?= $app['approval']; ?></b>
                                                                      <br>
                                                                      Request From : <b><?= $value['requester']; ?></b>
                                                                    <?php } ?>
                                                                       
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Submit To Approver
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                              'request/choose-approval',
                                                                              'project'=>(string)$value['_id'],
                                                                              'seller'=>$value2['seller'],
                                                                              'buyer'=>$user->account_name,
                                                                              'type' => 'direct',
                                       
                                                                              ]),'class' => 'dropdown-item choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                              <div class="dropdown-divider"></div>
                                                                              <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                  'request/choose-approval-level',
                                                                                  'project'=>(string)$value['_id'],
                                                                                  'seller'=>$value2['seller'],
                                                                                  'buyer'=>$user->account_name,
                                                                                  'type' => 'direct',
                                           
                                                                                  ]),'class' => 'dropdown-item choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>


                                                  
                                                                              
                                                                          </div>
                                                                      </div>

                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Purchase Requisition
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                              'project'=>(string)$value['_id'],
                                                                                              'seller'=>$value2['seller'],
                                                                                             'buyer'=>$value['buyers'][0]['buyer'],
                                                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                       
                                                                              
                                                                          </div>
                                                                      </div>
                                                                    
                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                    <?= Html::a('REJECT',FALSE, ['value'=>Url::to([
                                                                          'information/reject-by-buyer',
                                                                          'seller'=>$value2['seller'],
                                                                          'project'=>(string)$value['_id'],
                                                                          'buyer'=>$user->account_name,
                                                      
                                                                          ]),'class' => 'btn btn-warning','id'=>'cancelprbybuyer','style'=>'color:#fff;']) ?>




                                                                  </td>

                                                          <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Purchase Requisition
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                              
                                                                              
                                                                          </div>
                                                                      </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>



                                                            <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
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

                                                            <?php } elseif ($value2['status'] == 'Approve Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                  <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>



                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">

                                                                    <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    ],['target'=>'_blank','class'=>'dropdown-item']) ?>

                                                                        </div>
                                                                    </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>


                                                            <?php } elseif ($value2['status'] == 'Process') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-check',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Reject PR Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?>
                                                                    <?php } ?>
                                                                  </td>
                                                              <td><?php echo $value2['status']; ?></td>
                                                              <td>
                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit-next',
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

                                                            <?php } elseif ($value2['status'] == 'Approve') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    
                                                                    <?= Html::a('Choose Buyer',FALSE, ['value'=>Url::to([
                                                                    'request/choose-buyer',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'role'=>'buyer'
                                                   
                             
                                                                    ]),'class' => 'btn btn-secondary choose-buyer','id'=>'choose-buyer','title'=>'Purchase Requisition','style'=>'color : #292b2c']) ?>


                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>


                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'Reject PR') { ?>

                                                              <td>
                                                                <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                              </td>
                                                              <td><?php echo $value2['status']; ?></td>
                                                              <td>
                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                              </td>

                                                            <?php } elseif ($value2['status'] == 'Reject PR Next') { ?>

                                                              <td>
                                                                <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                              </td>
                                                              <td><?php echo $value2['status']; ?></td>
                                                              <td>
                                                                <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-resubmit-next',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    'approver'=>$value2['approver'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Requisition']) ?>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                              </td>

                                                              <?php } elseif ($value2['status'] == 'PO Revise') { ?>

                                                              <td>
                                                                <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                              </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                    <?= Html::a('Continue', ['request/direct-purchase-order-revise',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                        </div>
                                                                    </div>



                                                                  <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                              





                                                            <?php } elseif ($value2['status'] == 'Pass PR to Buyer To Proceed PO') { ?>

                                                                  <td>
                                                                    PR Approved By : 
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <b><?= $app['approval']; ?><br></b>
                                                                
                                                                    <?php } ?>
                                                                    Request From : <b><?= $value['requester']; ?></b>
                                                                       
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Submit To Approver
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                              
                                                                              <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                              'request/choose-approval',
                                                                              'project'=>(string)$value['_id'],
                                                                              'seller'=>$value2['seller'],
                                                                              'buyer'=>$user->account_name,
                                                                              'type' => 'direct',
                                       
                                                                              ]),'class' => 'dropdown-item choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                              <div class="dropdown-divider"></div>
                                                                                <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                'request/choose-approval-level',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$user->account_name,
                                                                                'type' => 'direct',
                                         
                                                                                ]),'class' => 'dropdown-item choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                        
                                                                          </div>
                                                                      </div>

                                                                      <div class="btn-group">
                                                                          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              Purchase Requisition
                                                                          </button>
                                                                          <div class="dropdown-menu animated flipInX">
                                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                              'project'=>(string)$value['_id'],
                                                                                              'seller'=>$value2['seller'],
                                                                                             'buyer'=>$value['buyers'][0]['buyer'],
                                                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                              
                                                                              
                                                                          </div>
                                                                      </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

                                                                    <?= Html::a('REJECT',FALSE, ['value'=>Url::to([
                                                                          'information/reject-by-buyer',
                                                                          'seller'=>$value2['seller'],
                                                                          'project'=>(string)$value['_id'],
                                                                          'buyer'=>$user->account_name,
                                                      
                                                                          ]),'class' => 'btn btn-warning','id'=>'cancelprbybuyer','style'=>'color:#fff;']) ?>



                                                                  </td>


                                                            <?php } elseif ($value2['status'] == 'Process') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                    
                                                                    
                                                                    <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-check',
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


                                                            <?php } elseif ($value2['status'] == 'Approve Next') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>

                                                                  <?= Html::a('Proceed To Purchase Order', ['request/direct-purchase-order',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$user->account_name,
                                                                    ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>


                                                                  </td>

                                                            <?php } elseif ($value2['status'] == 'PO In Progress') { ?>

                                                                  <td>
                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                      <?= $app['approval']; ?> : <?= $app['status']; ?>
                                                                      <br>
                                                                    <?php } ?>
                                                                  </td>
                                                                  <td>
                                                                    <?php echo $value2['status']; ?>
                                                                  </td>
                                                                  <td>
                                          
                                                                      <?= Html::a('Purchase Order', ['request/direct-purchase-order',
                                                                      'project'=>(string)$value['_id'],
                                                                      'seller'=>$value2['seller'],
                                                                      'buyer'=>$value2['PO_process_by'],
                                                                      ],['class'=>'btn btn-secondary','title'=>'Purchase Order']) ?>

                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Purchase Requisition
                                                                        </button>
                                                                        <div class="dropdown-menu animated flipInX">
                                                                            <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <?= Html::a('File', ['file/index',
                                                                    'project'=>(string)$value['_id'],
                                                                    ],['class'=>'btn btn-secondary','title'=>'File']) ?>

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

            <div class="tab-pane  p-20" id="log" role="tabpanel">
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
                                          <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                          <td><?= $value_info['seller'] ?></td>
                                          <td>
                                          <?php if ($value_info['status'] == 'Reject PR') { ?>

                                              <span class="label label-warning"><?= $value_info['status'] ?></span> By : <?= $value_info['by_approval'] ?>
  
                                          <?php } elseif ($value_info['status'] == 'Request Approval Next') { ?>

                                              <span class="label label-info"><?= $value_info['status'] ?></span>


                                          <?php } elseif ($value_info['status'] == 'Change Buyer') { ?>

                                              <span class="label label-info"><?= $value_info['status'] ?></span>
                                              
                                          <?php } ?>
                                          </td>
                                          <td>
                                            <?php if ($value_info['status'] == 'Reject PR') { ?>
                                                <span class="label label-inverse"><?= $value_info['date_reject'] ?></span>

                                            <?php } elseif ($value_info['status'] == 'Request Approval Next') { ?>
                                                <span class="label label-inverse"><?= $value_info['date_request'] ?></span>


                                            <?php } elseif ($value_info['status'] == 'Change Buyer') { ?>

                                                <span class="label label-inverse"><?= $value_info['date_change'] ?></span>

                                            <?php } ?>
                                          </td>
                                          
                                          <td>
                                              <?php if ($value_info['status'] == 'Reject PR') { ?>

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

                                              <?php } elseif ($value_info['status'] == 'Request Approval Next') { ?>

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

                                              <?php } elseif ($value_info['status'] == 'Change Buyer') { ?>

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


                                              

                                              <?php } elseif ($value_info['status'] == 'Revise PO') { ?>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Purchase Order
                                                    </button>
                                                    <div class="dropdown-menu animated flipInX">
                                                              <?= Html::a('<b>'.$value_info['purchase_order_no'].'</b>', [
                                                              'html/direct-purchase-order-html-inactive',
                                                              'log_id' => (string)$value_info['log_id'],
                                                              'buyer' => $value_info['by'],
                                                              ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                        
                                                        
                                                        
                                                    </div>
                                                </div>

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



                                              <?php } ?>

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
