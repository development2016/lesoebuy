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


$this->title = 'Order';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){


    $('#myTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    $('#order').DataTable();





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
                            <table class="table" id="order">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>PROJECT NO</th>
                                        <th>DETAILS</th>
                                        <th>INFOMATION</th>
                                        
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
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <tr>
                                                    <td><?php echo $value2['seller'] ?></td>
                                                    <td><?php echo $value2['status']; ?></td>
                                                    <td>
                                                    
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Purchase Order
                                                        </button>
                                                        <div class="dropdown-menu animated flipInX">
                                                            <?= Html::a('<b>'.$value2['purchase_order_no'].'</b>', ['html/direct-purchase-order-html',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller'],
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                 
                                                            
                                                        </div>
                                                    </div>


                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Purchase Requisition
                                                        </button>
                                                        <div class="dropdown-menu animated flipInX">
                                                            
                                                              <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html-inactive',
                                                                            'log_id'=>(string)$value2['last_id_approve_in_log'],
                                                                           'buyer'=>$value['buyers'][0]['buyer'],
                                                                            ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                                            
                                                        </div>
                                                    </div>


                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            File
                                                        </button>
                                                        <div class="dropdown-menu animated flipInX">
                                                            
                                                            
                                                            
                                                        </div>
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

            <div class="tab-pane  p-20" id="log" role="tabpanel">

            </div>
        </div>
      </div>
  </div>
</div>

