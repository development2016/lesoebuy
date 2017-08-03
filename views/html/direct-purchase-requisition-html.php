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




$country = LookupCountry::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['country']])->one();
$state = LookupState::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['state']])->one();





$this->title = 'Purchase Requisition';

$script = <<< JS
$(document).ready(function(){

    
}); 
JS;
$this->registerJs($script);

$amount = $sumAmount = $install = $showInstall = $sumInstall = $shipping = $showShipping = $sumShipping = $price = $showPrice = $sumPrice = 0;

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">


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
                        <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['seller'] ?></h4></div>
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
                                        <?php 
                                        $country_da = LookupCountry::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['country']])->one();
                                        $state_da = LookupState::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['state']])->one();
                                        ?>
                                        <?= $state_da->state; ?>,
                                        <?= $country_da->country; ?>

                                            <br>
                                            <b>P.I.C : </b> <?= $list[0]['sellers'][0]['warehouses'][0]['person_in_charge'] ?>
                                            <br>
                                            <b>Contact : </b> : <?= $list[0]['sellers'][0]['warehouses'][0]['contact'] ?>
                                            <br>
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
                        <div class="col-md-5"> <h4 class="bold">PR No : </h4></div>
                        <div class="col-md-7"> <h4 class="bold"><b><?= $list[0]['sellers'][0]['purchase_requisition_no'] ?></b></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">Date : </h4></div>
                        <div class="col-md-7"> <h4><?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['date_purchase_requisition'])); ?></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">Term : </h4></div>
                        <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['term'] ?></h4></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-5"> <h4 class="bold">Delivery Before : </h4></div>
                        <div class="col-md-7"> <h4>
                            <?php if (empty($list[0]['sellers'][0]['delivery_before'])) { ?>

                            <?php } else { ?>
                                <?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['delivery_before'])); ?>

                    
                            <?php } ?>


                        </h4>
                        </div>
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
                                        <h4>
                                            <b>Item Name : </b><span class="text-center"><?= $value['item_name']; ?></span>
                                            <br>
                                        </h4>
                                        <h4>
                                        <b>Brand : </b> <?= $value['brand'] ?>
                                        <br>
                                        <b>Model : </b> <?= $value['model'] ?>
                                        <br>
                                        <b>Specification : </b> <?= $value['specification'] ?>
                                     <br>
                                    <br>
                                    <b>Remark : </b><?= $value['remark'] ?>
                                        
                                            <br>
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
                            <span><?= $list[0]['sellers'][0]['tax']; ?>% 
                            <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?> : </span> <span class="pull-right font-red-sunglo bold">

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


         
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


