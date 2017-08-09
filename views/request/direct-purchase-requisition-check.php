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

$this->title = strtoupper('Purchase Requisition');
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#create').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('#temp').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });




    $('#cancelpr').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('#rqapp').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.edit_model').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.edit_sale').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.installation').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.shipping').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    
    $('.quantity').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });


    $('.unit_price').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.add_delivery').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('.deliveryBefore').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });
    $('.add_company').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('.change-approval').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.change-approval-level').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });
    
}); 
JS;
$this->registerJs($script);

$amount = $sumAmount = $install = $showInstall = $sumInstall = $shipping = $showShipping = $sumShipping = $price = $showPrice = $sumPrice = 0;

?>
<?php if(Yii::$app->session->hasFlash('change')) { ?>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert"></button>
         <?php echo  Yii::$app->session->getFlash('change'); ?>
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">


                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ADD ITEM
                    </button>
                    <div class="dropdown-menu animated flipInX">
                        <?= Html::a('Save to Item',FALSE, ['value'=>Url::to([
                            'information/item',
                            'seller'=>$seller,
                            'project'=>(string)$project,
                            'path' => 'check',
                            'approver' => $approver
                            ]),'class' => 'dropdown-item','id'=>'create','title' => 'This Feature Will Save To Item']) ?>

                        <?= Html::a('Add To PR',FALSE, ['value'=>Url::to([
                            'information/item-temp',
                            'seller'=>$seller,
                            'project'=>(string)$project,
                            'path' => 'check',
                            'approver' => $approver
                            ]),'class' => 'dropdown-item','id'=>'temp','title' => 'This Feature Will Only Save To PR']) ?>



                    </div>
                </div>






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
                            <?php if (empty($list[0]['sellers'][0]['seller'])) { ?>


                               <h4>
                                    <?= Html::a('Add',FALSE, ['value'=>Url::to([
                                    'information/add-company',
                                    'seller'=>$seller,
                                    'project'=>(string)$project,
                                    'approver'=>$approver,
                                    'buyer'=>$buyer,
                                    'path' => 'check'
                                    ]),'class' => 'add_company text-primary','id'=>'add_company','style'=>'cursor:pointer;']) ?>


                               </h4>


                            <?php } else { ?>

                                <h4>
                                <?= $list[0]['sellers'][0]['seller'] ?>
                                <br>
                                <?php if (!isset($list[0]['sellers'][0]['att']) || empty($list[0]['sellers'][0]['att'])) { ?>
                              
                                <?php } else { ?>
 
                                    <b>Attention To : </b><?= $list[0]['sellers'][0]['att'] ?>
                                <?php } ?>
                                    <br>
                                <?php if (!isset($list[0]['sellers'][0]['att_tel']) || empty($list[0]['sellers'][0]['att_tel'])) { ?>
                               
                                <?php } else { ?>
 
                                    <b>Contact : </b><?= $list[0]['sellers'][0]['att_tel'] ?>
                                <?php } ?>
                                <br>
                                <?php if (!isset($list[0]['sellers'][0]['att_email']) || empty($list[0]['sellers'][0]['att_email'])) { ?>
                                    
                                <?php } else { ?>
                                    <b>Email : </b><?= $list[0]['sellers'][0]['att_email'] ?>
                                <?php } ?>
                                
                                <br>
                                  <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                    'information/edit-company',
                                    'seller'=>$seller,
                                    'project'=>(string)$project,
                                    'approver'=>$approver,
                                    'buyer'=>$buyer,
                                    'path' => 'check'
                                    ]),'class' => 'add_company text-primary','id'=>'add_company','style'=>'cursor:pointer;']) ?>


                                </h4>

                            <?php } ?>




                        </h4></div>
                    </div>

                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">Delivery Address : </h4></div>
                        <div class="col-md-7"> 
                                <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>
                                   
                                   <h4>
                                        <?= Html::a('Add',FALSE, ['value'=>Url::to([
                                        'information/add-delivery',
                                        'seller'=>$seller,
                                        'project'=>(string)$project,
                                        'approver'=>$approver,
                                        'buyer'=>$buyer,
                                        'path' => 'check'
                                        ]),'class' => 'add_delivery','id'=>'add_delivery','style'=>'cursor:pointer;']) ?>


                                   </h4>
                                   
                                <?php } else { ?>

                                    <h4>
                                        <?= $list[0]['sellers'][0]['warehouses'][0]['warehouse_name'] ?>
                                        <br>
                                        <?= $list[0]['sellers'][0]['warehouses'][0]['address'] ?>,
                                        <?php 
                                        $country_da = LookupCountry::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['country']])->one();
                                        $state_da = LookupState::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['state']])->one();
                                        ?>
                                        <?= $state_da->state; ?>,
                                        <?= $country_da->country; ?>
                                        <br>
                                        <b>P.I.C : </b><?= $list[0]['sellers'][0]['warehouses'][0]['person_in_charge'] ?>
                                        <br>
                                        <b>Contact : </b><?= $list[0]['sellers'][0]['warehouses'][0]['contact'] ?>
                                        <br>
                                        <b>Email : </b><?= $list[0]['sellers'][0]['warehouses'][0]['email'] ?>
                                        <br>
                                        <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                        'information/edit-delivery',
                                        'seller'=>$seller,
                                        'project'=>(string)$project,
                                        'approver'=>$approver,
                                        'buyer'=>$buyer,
                                        'path' => 'check'
                                        ]),'class' => 'add_delivery text-primary','id'=>'add_delivery','style'=>'cursor:pointer;']) ?>

                                    </h4>


                                <?php } ?>

                        </div>
                    </div>


                </div>

                <div class="col-md-2">

                </div>

                <div class="col-md-3">
                    <div class="row static-info">
                        <div class="col-md-6"> <h4 class="bold">PR No : </h4></div>
                        <div class="col-md-6"> <h4 class="bold"><?= $list[0]['sellers'][0]['purchase_requisition_no'] ?></h4></div>
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
                            <?= Html::a('Add',FALSE, ['value'=>Url::to([
                                        'information/add-before',
                                        'seller'=>$seller,
                                        'project'=>(string)$project,
                                        'approver'=>$approver,
                                        'buyer'=>$buyer,
                                        'path' => 'check'
                                        ]),'class' => 'deliveryBefore text-primary','id'=>'deliveryBefore','style'=>'cursor:pointer;']) ?>
                            <?php } else { ?>
                                <?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['delivery_before'])); ?>
                                <br>
                                <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                        'information/edit-before',
                                        'seller'=>$seller,
                                        'project'=>(string)$project,
                                        'approver'=>$approver,
                                        'buyer'=>$buyer,
                                        'path' => 'check'
                                        ]),'class' => 'deliveryBefore text-primary','id'=>'deliveryBefore','style'=>'cursor:pointer;']) ?>
                    
                            <?php } ?>

                        </h4></div>
                    </div>



                </div>


            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
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
                                <th><h4><span class="bold">ACTION</span></h4></th>
                          
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
                                    <h4>
                                        <b>Item Name : </b><span class="text-center"><?= $value['item_name']; ?></span>
                                        <br>
                                        <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                        'information/sale-item-update',
                                        'project'=>(string)$list[0]['_id'],
                                        'seller'=>$list[0]['sellers'][0]['seller'],
                                        'arrayItem' => $arrayItem,
                                        'path' => 'check',
                                        'approver' => $approver
                                        ]),'class'=>'edit_model text-primary','style'=>'cursor:pointer;']); ?>
                                    </h4>

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
                                        <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                        'information/sale-detail-update',
                                        'project'=>(string)$list[0]['_id'],
                                        'seller'=>$list[0]['sellers'][0]['seller'],
                                        //'item_id'=>$value['item_id'],
                                        'arrayItem' => $arrayItem,
                                        'path' => 'check',
                                        'approver' => $approver
                                        ]),'class'=>'edit_sale text-primary','style'=>'cursor:pointer;']); ?>



                                    </h4>


                                </td>
                                <td>
                                    <h4><span>

                                        <?php if (empty($value['install'])) { ?>

                                            <?= Html::a('0.00',FALSE, ['value'=>Url::to([
                                            'information/sale-installation-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                           // 'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'installation text-primary','style'=>'cursor:pointer;']); ?>

                                        <?php } elseif ($value['install'] == 'No') { ?>

                                            <?= Html::a($value['install'],FALSE, ['value'=>Url::to([
                                            'information/sale-installation-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'installation text-primary','style'=>'cursor:pointer;']); ?>

                                        <?php } else { ?>

                                            <?= Html::a($showInstall = number_format((float)$value['installation_price'],2,'.',','),FALSE, ['value'=>Url::to([
                                            'information/sale-installation-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'installation text-primary','style'=>'cursor:pointer;']); ?>

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

                                            <?= Html::a('0.00',FALSE, ['value'=>Url::to([
                                            'information/sale-shipping-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'shipping text-primary','style'=>'cursor:pointer;']); ?>

                                        <?php } elseif ($value['shipping'] == 'No') { ?>

                                            <?= Html::a($value['shipping'],FALSE, ['value'=>Url::to([
                                            'information/sale-shipping-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                           // 'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'shipping text-primary','style'=>'cursor:pointer;']); ?>

                                        <?php } else { ?>

                                            <?= Html::a($showShipping = number_format((float)$value['shipping_price'],2,'.',','),FALSE, ['value'=>Url::to([
                                            'information/sale-shipping-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                            ]),'class'=>'shipping text-primary','style'=>'cursor:pointer;']); ?>

                                            <?php 
                                            $shipping = $value['shipping_price']; 

                                            $sumShipping += $shipping;  ?>
                                            

                                        <?php } ?>
                                        




                                    </span></h4>
                                </td>
                                <td>
                                    <h4><span>
             
                                        
                                    <?= Html::a($value['quantity'],FALSE, ['value'=>Url::to([
                                        'information/sale-quantity-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                        ]),'class'=>'quantity text-primary','style'=>'cursor:pointer;']); ?>



                                    </span></h4>
                                </td>
                                <td>
                                    <h4><span>
                                
                                        
                                        <?= Html::a($showPrice = number_format((float)$value['cost'],2,'.',','),FALSE, ['value'=>Url::to([
                                        'information/sale-cost-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'check',
                                             'approver' => $approver
                                        ]),'class'=>'unit_price text-primary','style'=>'cursor:pointer;']); ?>

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
                                <td>
                                    <div class="margin-bottom-5">

                                            <?= Html::a('REMOVE', [
                                            'information/sale-remove',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $value['item_id'],
                                            'path' => 'check',
                                            'approver' => $approver
                                            ], ['class' => 'btn btn-sm btn-danger','title'=>'Remove']) ?>

                                        </div>
                                </td>

                            </tr>
                            <?php } ?> 
                        </tbody>


                    </table>

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
                            <span><?= $list[0]['sellers'][0]['tax']; ?>% 
                            <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?>


                             : </span> <span class="pull-right font-red-sunglo bold">

                                <?php  echo number_format((float)$deductGst,2,'.',','); ?>
                                
                            </span>
                        </h4>
                        <br>
                        <h3>
                            <span><b>Total</b> (<?= $list[0]['sellers'][0]['tax']; ?>% <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'].' Included' ; ?> ) : </span> <span class="pull-right bold">
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

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            CHANGE APPROVER
                        </button>
                        <div class="dropdown-menu animated flipInX">
                            <?= Html::a('Approver',FALSE, ['value'=>Url::to([
                                'request/change-approval-next',
                                'seller'=>$seller,
                                'project'=>(string)$project,
                                'buyer'=>$buyer,
                                'type' => 'change',

                                ]),'class' => 'dropdown-item change-approval','id'=>'change-approval','title'=>'Choose Approver']) ?>

                            <?= Html::a('Approver By Level',FALSE, ['value'=>Url::to([
                                'request/change-approval-level-next',
                                'seller'=>$seller,
                                'project'=>(string)$project,
                                'buyer'=>$buyer,
                                'type' => 'change',

                                ]),'class' => 'dropdown-item change-approval-level','id'=>'change-approval-level','title'=>'Approver By Level']) ?>



                        </div>
                    </div>







                       <?= Html::a('REQUEST APPROVAL',FALSE, ['value'=>Url::to([
                        'generate/generate-direct-purchase-requisition-next',
                        'seller'=>$seller,
                        'project'=> (string)$project,
                        'approver'=>$approver,
                        'buyer'=> $buyer,
                        ]),'class' => 'btn btn-info pull-right','id'=>'rqapp','style'=>'color:#fff;']) ?>



                    </div>
                </div>





            </div>
        </div>
    </div>
</div>


        