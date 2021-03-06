<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\LookupModel;
use app\models\LookupBrand;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupLeadTime;
$this->title = 'Purchase Requisition';

$script = <<< JS
$(document).ready(function(){

    $('#reject').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });
    
}); 
JS;
$this->registerJs($script);

$amount = $sumAmount = $install = $showInstall = $sumInstall = $shipping = $showShipping = $sumShipping = $price = $showPrice = $sumPrice = 0;




?>


<?php if (empty($notification->remark)) { ?>
 
<?php } else { ?>

<div class="alert alert-info" ><strong>Request : </strong> <?= $notification->remark; ?></div>
    
<?php } ?>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-lg-4">
                        <b>Title : </b> <?= $list[0]['title']; ?>
                    </div>
                    <div class="col-lg-4">
                        <b>Description : </b> <?= $list[0]['description']; ?>
                    </div>

                    <div class="col-lg-4">
                        <b>Due Date : </b> <?= $list[0]['due_date']; ?>
                        
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card  earning-widget">
                            <div class="card-header">
                                <div class="card-actions">
                                    <a class="" data-action="collapse"><i class="ti-minus"></i></a>

                                </div>
                                <h4 class="card-title m-b-0">Uploaded File</h4>
                            </div>
            <div class="card-block collapse show">

       


                <?php if (empty($fileUpload)) { ?>

                    <?php echo 'No File Uploaded'; ?>

                <?php } else { ?>

                        <table class="table table-hover">
                            <thead>
                                <tr>

                                    <th>Filename</th>
                                    <th>Date Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach ($fileUpload[0]['filename'] as $key_file => $value_file) { $i++;?>
                                    <tr>
                                        <td>
                                            <?php if ($value_file['ext'] == 'pdf') { ?>
                                                <i class="mdi mdi-file-pdf-box" style="color: red;font-size:30px;"></i>
                                            <?php } elseif ($value_file['ext'] == 'xlsx') { ?>
                                                <i class="mdi mdi-file-excel-box" style="color: green;font-size:30px;"></i>
                                            <?php } elseif ($value_file['ext'] == 'docx') { ?>
                                                <i class="mdi mdi-file-word-box" style="color: blue;font-size:30px;"></i>
                                            <?php } else { ?>
                                                <i class="mdi mdi-file"></i>
                                            <?php } ?>

                                        <?php echo $value_file['file']; ?></td>
                                        <td><?php echo $value_file['date_create']; ?></td>
                                        <td>
                                            <?= Html::a('View', ['file/view', 
                                            'path' => $value_file['path'],
                                            'extension' => $value_file['ext'],
                                            ], ['class' => 'btn btn-warning btn-sm','title'=>'View Upload File','target' => '_BLANK']) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>



                <?php } ?>







            </div>
        </div> 
    </div>
</div>





<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">

                <h4 class="card-title"><?= Html::encode($this->title) ?> - <?= $list[0]['project_no']; ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6>

            <div class="row">

                <div class="col-lg-3">
                    <?php if (empty($companyBuyer->logo)) { ?>
                        <img src="<?php echo Yii::$app->request->baseUrl;?>/image/logo.png" class="img-responsive" alt="" />
                    <?php } else { ?>
                        <img src="<?php echo Yii::$app->request->baseUrl;?>/<?php echo $companyBuyer->logo; ?>" class="img-responsive" alt="" />
                    <?php } ?>
                </div>
                
                <div class="col-lg-6">

                    <h3>
                    <?= $companyBuyer->company_name ?>
                    </h3>
                    <h6>Co.No. <?= $companyBuyer->company_registeration_no ?> , GST Registeration No. : <?= $companyBuyer->tax_no ?></h6>
                    <h4>
                    <?= $companyBuyer->address ?> , <?= $companyBuyer->zip_code ?> , <?= $companyBuyer->city ?> , <?= $companyBuyer->states->state ?> , <?= $companyBuyer->countrys->country ?>
                    </h4>
                    <h5>
                        <span class="bold">TEL : </span> <?= $companyBuyer->telephone_no ?>
                        &nbsp;
                        <span class="bold">FAX : </span> <?= $companyBuyer->fax_no ?>
                    </h5>
                    <h5>
                        <span class="bold">EMAIL : </span> <?= $companyBuyer->email ?>
                    </h5>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-xs-4">
                </div>
                <div class="col-md-5 col-xs-5">
                    <h1 class="bold">
                        PURCHASE REQUISITION
                    </h1>
                </div>
                 <div class="col-md-3 col-xs-3">
                 </div>
            </div>
            <hr>
            <div class="row">

                <div class="col-md-7">
                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">To : </h4></div>
                        <div class="col-md-7"> <h4>
                                <?= $list[0]['sellers'][0]['seller'] ?>
                                <br>
                                <?php if (!isset($list[0]['sellers'][0]['att']) || empty($list[0]['sellers'][0]['att'])) { ?>
                              
                                <?php } else { ?>
 
                                    <b>Attention To : </b><?= $list[0]['sellers'][0]['att'] ?><br>
                                <?php } ?>
                                    
                                <?php if (!isset($list[0]['sellers'][0]['att_tel']) || empty($list[0]['sellers'][0]['att_tel'])) { ?>
                               
                                <?php } else { ?>
 
                                    <b>Contact : </b><?= $list[0]['sellers'][0]['att_tel'] ?><br>
                                <?php } ?>
                                
                                <?php if (!isset($list[0]['sellers'][0]['att_email']) || empty($list[0]['sellers'][0]['att_email'])) { ?>
                                    
                                <?php } else { ?>
                                    <b>Email : </b><?= $list[0]['sellers'][0]['att_email'] ?><br>
                                <?php } ?>
                                
                            
                            
                        </h4></div>
                    </div>

                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">Delivery Address : </h4></div>
                        <div class="col-md-7"> 
                                    <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>
               

                                       
                                    <?php } else { ?>

                                        <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>
                                       
                                        <?php } else { ?>



                                        <h4>
                                            <?= $list[0]['sellers'][0]['warehouses'][0]['warehouse_name'] ?>
                                            <br>
                                        <?= $list[0]['sellers'][0]['warehouses'][0]['address'] ?>,
                                        <?php if (empty($list[0]['sellers'][0]['warehouses'][0]['postcode']) || !isset($list[0]['sellers'][0]['warehouses'][0]['postcode'])) {
                                            
                                          
                                        } else {

                                            echo $list[0]['sellers'][0]['warehouses'][0]['postcode'];
                                        }

                                         ?>,


                                        <?php 
                                        $country_da = LookupCountry::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['country']])->one();
                                        $state_da = LookupState::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['state']])->one();
                                        ?>
                                        <?= $state_da->state; ?>,
                                        <?= $country_da->country; ?>
                                            <br>
                                            <b>P.I.C : </b> <?= $list[0]['sellers'][0]['warehouses'][0]['person_in_charge'] ?>
                                            <br>
                                            <b>Contact : </b><?= $list[0]['sellers'][0]['warehouses'][0]['contact'] ?>
                                            <br>
                                        <?php if (empty($list[0]['sellers'][0]['warehouses'][0]['fax']) || !isset($list[0]['sellers'][0]['warehouses'][0]['fax'])) { ?>
                                            
                                            <b>Fax : </b>
                                            <br>
                                        <?php } else { ?>

                                            <b>Fax : </b><?= $list[0]['sellers'][0]['warehouses'][0]['fax'] ?>
                                            <br>
                                        <?php } ?>

                                            
                                            <b>Email : </b><?= $list[0]['sellers'][0]['warehouses'][0]['email'] ?>
                                
                                        </h4>

                                        <?php } ?>

                                    <?php } ?>

                        </div>
                    </div>


                </div>

                <div class="col-md-2">

                </div>

                <div class="col-md-3">
                    <div class="row static-info">
                        <div class="col-md-6"> <h4 class="bold">PR No : </h4></div>
                        <div class="col-md-6"> <h4 class="bold"><b><?= $list[0]['sellers'][0]['purchase_requisition_no'] ?></b></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-6"> <h4 class="bold">Date : </h4></div>
                        <div class="col-md-6"> <h4><?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['date_purchase_requisition'])); ?></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-6"> <h4 class="bold">Term : </h4></div>
                        <div class="col-md-6"> <h4><?= $list[0]['sellers'][0]['term'] ?></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-6"> <h4 class="bold">Delivery Before : </h4></div>
                        <div class="col-md-6"> <h4>
       
                       <?php if (empty($list[0]['sellers'][0]['delivery_before'])) { ?>

                            <?php } else { ?>
                                <?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['delivery_before'])); ?>

                    
                            <?php } ?>


                        </h4></div>
                    </div>

                </div>


            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                <div class="table-responsive m-t-40" style="clear: both;">
                    <table class="table">

                        <thead>
                            <tr>
                                <th><h4><span class="bold">NO</span></h4></th>
                                <th><h4><span class="bold">ITEM</span></h4></th>
                                <th><h4><span class="bold">C.I.T</span></h4></th>
                                <th><h4><span class="bold">FREIGHT</span></h4></th>
                                <th><h4><span class="bold">QTY</span></h4></th>
                                <th><h4><span class="bold">U / PRICE</span></h4></th>
                                <th><h4><span class="bold">AMOUNT</span></h4></th>
         
                            </tr>
                        </thead>
                        <tbody>
                            <?php $arrayItem = -1; $i=0; foreach ($list[0]['sellers'][0]['items'] as $key => $value) { $i++; $arrayItem++; ?>
                                <tr>
                                    <td>
                                        <h4><span><?= $i; ?></span></h4>
                                    </td>
                                    <td>
                                        <h4><b>Item Code : </b><?= $value['item_code'] ?></h4>
                                        <h4><b>Item Name : </b><span class="text-center"><?= $value['item_name']; ?></span></h4>
                                        <h4>

                                        <b>Specification : </b> <?= $value['specification'] ?>
                                        <br>
                                        <b>Brand : </b> <?= $value['brand'] ?>
                                        <br>
                                        <b>Model : </b> <?= $value['model'] ?>
                                        <br>
                                        <br>
                                        <b>Remark : </b><?= $value['remark'] ?>
                                        <br>
                                    <?php if (empty($value['lead_time'])) { ?>
                                        <b>Lead Time : </b> 
                                    <?php } else { ?>
                                        <?php $lead = LookupLeadTime::find()->where(['id'=>$value['lead_time']])->one(); ?>
                                        <b>Lead Time : </b> <?= $lead->lead_time ?>
                                    <?php } ?>
                                        </h4>
 
                                    </td>
                                    <td>
                                        <h4><span>

                                            <?php if (empty($value['install'])) { ?>

                                                <?= '0.00'; ?>

                                            <?php } elseif ($value['install'] == 'No') { ?>

                                                <?= $value['install']; ?>

                                            <?php } else { ?>

                                                <?= $showInstall = number_format((float)$value['installation_price'],2,'.',','); ?>

                                                <?php 
                                                    $install = $value['installation_price']; 

                                                    $sumInstall += $install;
                                                  ?>

                                            <?php } ?>

                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>

                                            <?php if (empty($value['shipping'])) { ?>

                                                <?= '0.00'; ?>

                                            <?php } elseif ($value['shipping'] == 'No') { ?>

                                                <?= $value['shipping']; ?>

                                            <?php } else { ?>

                                                <?= $showShipping = number_format((float)$value['shipping_price'],2,'.',','); ?>

                                                <?php 
                                                $shipping = $value['shipping_price']; 

                                                $sumShipping += $shipping;  ?>
                                                

                                            <?php } ?>
                                            




                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                 
                                            
                                        <?= $value['quantity']; ?>



                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                                    
                                            
                                            <?= $showPrice = number_format((float)$value['cost'],2,'.',','); ?>

                                                <?php 
                                                $price = $value['cost']; 

                                                $sumPrice += $price;  ?>
                                                



                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span class="bold">
                                        <?php $amount =  $value['quantity'] * $value['cost']; 

                                        echo number_format((float)$amount,2,'.',','); 
                                        $sumAmount += $amount;


                                        ?></span></h4>
                                    </td>


                                </tr>
                            <?php } ?> 
                        </tbody>


                    </table>
                    </div>

                </div>
            </div>
            <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <h4>
                            <span>Sub-Total : </span> 
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumAmount,2,'.',','); ?>
                                    
                            </span>
                        </h4>
                        <h4>
                            <span>Freight Charge : </span>
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumShipping,2,'.',','); ?>
                            </span>
                        </h4>
                        <h4>
                            <span>Commissioning,Installation, & Training Charge (C.I.T) : </span>
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumInstall,2,'.',','); ?>
                            </span>
                        </h4>

                        <?php 
                               $total = $sumAmount + $sumShipping + $sumInstall;

                               $deductGst = $total * ($list[0]['sellers'][0]['tax'] / 100 );

                                
                        ?>
                        <h4>
                            <span><?= $list[0]['sellers'][0]['tax']; ?>% <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?> : </span> <span class="pull-right font-red-sunglo bold">

                                <?php  echo number_format((float)$deductGst,2,'.',','); ?>
                                
                            </span>
                        </h4>
                        <br>
                        <h3>
                            <span><b>Total</b> (<?= $list[0]['sellers'][0]['tax']; ?>% <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?>) : </span> <span class="pull-right bold">
                                <?php

                                    $grandTotal = $total + $deductGst;
                                    echo number_format((float)$grandTotal,2,'.',','); 

                                 ?>
                            </span>
                        </h3>


                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">

                    <?= Html::a('REJECT PR',FALSE, ['value'=>Url::to([
                    'request/reject-pr',
                    'seller'=>$seller,
                    'project'=> (string)$project,
                    'approver'=>$approver,
                    'buyer'=> $buyer,
                    ]),'class' => 'btn btn-warning','id'=>'reject','style'=>'color:#fff;']) ?>

                    

                        <?= Html::a('APPROVE', [
                        'request/approve',
                        'seller'=>$seller,
                        'project'=> (string)$project,
                        'approver' => $approver,
                        ], [
                        'class' => 'btn btn-info pull-right',
  
                        ]) ?>

                    </div>
                </div>




            </div>
        </div>
    </div>
</div>






