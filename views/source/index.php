<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Create';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#example').DataTable();
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

    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })



}); 
JS;
$this->registerJs($script);

?>

<div class="row">
  <div class="col-md-12">
      <div class="card">
        <div class="card-block">
            <?= Html::a('CREATE PR', ['offline/index'],['class'=>'btn btn-info pull-right','title'=>'Create PR']) ?>

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
                  
                  <table class="table" id="example">
                      <thead >
                        <tr>
                            <th>No</th>
                            <th>Project No</th>
                            <th>Details</th>
                            <th>Information</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $i=0; foreach ($model as $key => $value) { $i++;?>
                        <tr>
                          <td><?= $i; ?></td>
                          <td><?= $value['project_no'] ?></td>
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
                                      <th>Status</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                  <tr>
                                      <td>
                                        <?php if (empty($value2['seller'])) {
                                          
                                        } else {

                                          echo $value2['seller'];

                                        }?>
                                        
                                      </td>
                                      <td><?php echo $status =  $value2['status'] ?></td>
                                      <td>
                                          
                                          <?php if ($status == "Project Created") { ?>

                                                <?php if (empty($value2['approval'])) { ?>

                                                  <div class="btn-group">
                                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                          Choose Approver
                                                      </button>
                                                      <div class="dropdown-menu animated flipInX">
                                                          <?= Html::a('Approver',FALSE, ['value'=>Url::to([
                                                                'source/choose-approval',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                'type' => 'direct',
                         
                                                                ]),'class' => 'dropdown-item choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>
                                                          <div class="dropdown-divider"></div>
                                                          <?= Html::a('Approver By Level',FALSE, ['value'=>Url::to([
                                                                'source/choose-approval-level',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>$value2['seller'],
                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                'type' => 'direct',
                         
                                                                ]),'class' => 'dropdown-item choose-approval-level','id'=>'choose-approval-level','title'=>'Approver By Level']) ?>
                                                          
                                                      </div>
                                                  </div>

                                                <?php } else { ?>

                                                <?= Html::a('Purchase Requisition', ['source/direct-purchase-requisition',
                                                    'project'=>(string)$value['_id'],
                                                    'seller'=>(string)$value2['seller'],
                                                    'approver'=>$value2['approver'],
                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                    ],['class'=>'btn btn-primary','title'=>'Purchase Requisition']) ?>
                                     

                                                <?php }  ?>


                                         <?php } elseif ($status == "In Process") { ?>

                                            <?= Html::a('Continue', ['offline/project',
                                                    'project'=>(string)$value['project_no'],
                                                    ],['class'=>'btn btn-primary','title'=>'In Process']) ?>


                
                                           
                                         <?php } ?>


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
                 


                </div>
            </div>
            <div class="tab-pane  p-20" id="log" role="tabpanel">
              
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
                          <td><?= $value_log['project_no'] ?></td>
                          <td>
                                  <ul class="list-group">
                                      <li class="list-group-item"><b>Title</b> : <?= $value_log['title']; ?></li>
                                      <li class="list-group-item"><b>Description</b> : <?= $value_log['description']; ?></li>
                                      <li class="list-group-item"><b>Due Date</b> : <?= $value_log['due_date']; ?></li>
                                      <li class="list-group-item"><b>Date Create</b> : <?= $value_log['date_create']; ?></li>

                                  </ul>

                          </td>
                          <td>
                              <table class="table table-bordered" >
                                <thead class="thead-default">
                                  <tr>
                                      <th>SELLER NAME</th>
                                      <th>STATUS</th>
                                      <th>ACTION</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($value_log['sellers'] as $key => $value2_log) { ?>
                                  <tr>
                                      <td><?php echo $value2_log['seller'] ?></td>
                                      <td><?php echo $status =  $value2_log['status'] ?></td>
                                      <td>
                                          
                                          <div class="btn-group">
                                            <button type="button" class="btn btn-default">Purchase Requisition</button>
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <span class="caret"></span>
                                              <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                              <li>
                                                <?= Html::a('<b>'.$value2_log['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html-cancel',
                                                      'project'=>(string)$value_log['_id'],
                                                      'seller'=>$value2_log['seller'],
                                                      'buyer'=>$value_log['buyers'][0]['buyer'],
                                                      ],['target'=>'_blank']) ?>
                                              </li>
               
                                            </ul>
                                          </div>


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

        </div>

      </div>
  </div>
</div>

        

