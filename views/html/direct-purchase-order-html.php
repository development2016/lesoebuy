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




$this->title = 'Purchase Order';

$script = <<< JS
$(document).ready(function(){

    
}); 
JS;
$this->registerJs($script);

$amount = $sumAmount = $install = $showInstall = $sumInstall = $shipping = $showShipping = $sumShipping = $price = $showPrice = $sumPrice = 0;

?>

<div>
    <span style="font-size: 30px;font-weight: bold;"><?= $companyBuyer->company_name ?></span>
    <span style="font-size: 11px;">Co.No. <?= $companyBuyer->company_registeration_no ?> , GST Registeration No. : <?= $companyBuyer->tax_no ?></span>
    <br>
    <span style="font-size: 15px;">
      <?= $companyBuyer->address ?> , <?= $companyBuyer->zip_code ?> , <?= $companyBuyer->city ?> , <?= $companyBuyer->states->state ?> , <?= $companyBuyer->countrys->country ?>
    </span>
    <br>
    <span style="font-size: 15px;">
      <span style="font-size: 15px;font-weight: bold;">TEL : </span> <?= $companyBuyer->telephone_no ?>
      &nbsp;
      <span style="font-size: 15px;font-weight: bold;">FAX : </span> <?= $companyBuyer->fax_no ?>
      &nbsp;
      <span style="font-size: 15px;font-weight: bold;">EMAIL : </span> <?= $companyBuyer->email ?>

    </span>

</div>
<p></p>
<br>
<div>
    <h2 style="font-size: 22px;font-weight: bold;text-align: center;">PURCHASE ORDER</h2>
</div>
<hr style="   border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    ">
<p></p>
<br>
<div>
  <table border="0" width="100%">
      <tr>
          <td style="width: 130px;vertical-align: top;"><span style="font-size: 15px;">To</span></td>
          <td style="width: 5px;vertical-align: top;"><span style="font-size: 15px;">:</span></td>
          <td style="width: 430px;">
                                <?= $list[0]['sellers'][0]['seller'] ?>
                                <br>
                                <?php if (!isset($list[0]['sellers'][0]['att']) || empty($list[0]['sellers'][0]['att'])) { ?>
                              
                                <?php } else { ?>
 
                                    <b>Attention To : </b><?= $list[0]['sellers'][0]['att'] ?>
                                    <br>
                                <?php } ?>
                                    
                                <?php if (!isset($list[0]['sellers'][0]['att_tel']) || empty($list[0]['sellers'][0]['att_tel'])) { ?>
                               
                                <?php } else { ?>
 
                                    <b>Contact : </b><?= $list[0]['sellers'][0]['att_tel'] ?>
                                    <br>
                                <?php } ?>
                                
                                <?php if (!isset($list[0]['sellers'][0]['att_email']) || empty($list[0]['sellers'][0]['att_email'])) { ?>
                                    
                                <?php } else { ?>
                                    <b>Email : </b><?= $list[0]['sellers'][0]['att_email'] ?>
                                    <br>
                                <?php } ?>
                                
          </td>
          <td style="width: 80px;vertical-align: top;"><span style="font-size: 15px;">PO No</span></td>
          <td style="width: 5px;vertical-align: top;"><span style="font-size: 15px;">:</span></td>
          <td style="width: 130px;vertical-align: top;">
              
            <span style="font-weight: bold;">
            <?= $list[0]['sellers'][0]['purchase_order_no'] ?>
            <?php if (empty($list[0]['sellers'][0]['purchase_order_no_revise'])) {
                           
                        } else {
                            echo $list[0]['sellers'][0]['purchase_order_no_revise'];
                        }  ?></span>

          </td>
      </tr>
      <tr>
          <td style="width: 130px;"></td>
          <td style="width: 5px;vertical-align: top;"></td>
          <td style="width: 430px;"></td>
          <td style="width: 80px;"><span style="font-size: 15px;">Date</span></td>
          <td style="width: 5px;vertical-align: top;"><span style="font-size: 15px;">:</span></td>
          <td style="width: 130px;">
            <span style="font-size: 15px;"><?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['date_purchase_order'])); ?></span>

          </td>
      </tr>



      <tr >
          <td style="width: 130px;vertical-align: top;" rowspan="2"><span style="font-size: 15px;" >Delivery Address</span></td>
          <td style="width: 5px;vertical-align: top;" rowspan="2">:</td>
          <td style="width: 430px;" rowspan="2">
            <span style="font-size: 15px;">                                    

            <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>

                                       
                <?php } else { ?>

                    <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>
                   
                    <?php } else { ?>

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



                    <?php } ?>

                <?php } ?>
                                    </span>


          </td>
          <td style="vertical-align: top;width: 80px;"><span style="font-size: 15px;">Term</span></td>
          <td style="width: 5px;vertical-align: top;"><span style="font-size: 15px;">:</span></td>
          <td style="vertical-align: top;width: 130px;"><span style="font-size: 15px;"><?= $list[0]['sellers'][0]['term'] ?></span></td>
      </tr>
      <tr>
          <td style="vertical-align: top;width: 80px;"><span style="font-size: 15px;">Delivery Before</span></td>
          <td style="width: 5px;vertical-align: top;"><span style="font-size: 15px;">:</span></td>
          <td style="vertical-align: top;width: 130px;"><span style="font-size: 15px;">
          <?php if (empty($list[0]['sellers'][0]['delivery_before'])) { ?>

                            <?php } else { ?>
                                <?php echo $new = date('d F Y', strtotime($list[0]['sellers'][0]['delivery_before'])); ?>

                    
                            <?php } ?>
                              
                            </span></td>
      </tr>



  </table>
</div>

<p></p>
<br>
<div>
  <table width="100%" border="0" >
      <tr>
          <td style="padding: 10px;background-color: #dedede;"><span style="font-size: 15px;font-weight: bold;">NO</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">ITEM</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">C.I.T</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">FREIGHT</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">QTY</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">U / PRICE</span></td>
          <td style="padding: 10px;background-color: #dedede;""><span style="font-size: 15px;font-weight: bold;">AMOUNT</span></td>

      </tr>
      <?php $arrayItem = -1; $i=0; foreach ($list[0]['sellers'][0]['items'] as $key => $value) { $i++; $arrayItem++; ?>
      <tr class="border_bottom">
          <td style="padding: 10px;"><span style="font-size: 15px;"><?= $i; ?></span></td>
          <td style="padding: 10px;">
              <b>Item Code : </b><?= $value['item_code'] ?>
              <br>
              <b>Item Name : </b><?= $value['item_name']; ?>
              <br>
              <b>Specification : </b> <?= $value['specification'] ?>
              <br>
              <b>Brand : </b> <?= $value['brand'] ?>
              <br>
              <b>Model : </b> <?= $value['model'] ?>
          </td>
          <td style="padding: 10px;">
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
          </td>
          <td style="padding: 10px;">
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
          </td>
          <td style="padding: 10px;">
                <?= $value['quantity']; ?>
          </td>
          <td style="padding: 10px;">
                <?= $showPrice = number_format((float)$value['cost'],2,'.',','); ?>

                    <?php 
                    $price = $value['cost']; 

                    $sumPrice += $price;  ?>
          </td>
          <td style="padding: 10px;">
              <b><?php $amount =  $value['quantity'] * $value['cost']; 

                                        echo number_format((float)$amount,2,'.',','); 
                                        $sumAmount += $amount;


                                        ?></b>
          </td>

      </tr>


      <?php } ?>
  </table>
</div>
<br>

<div>
  <table width="100%" border="0">
      <tr>
          <td style="padding: 2px;width: 600px;"><span style="font-size: 15px;">Sub-Total :</span></td>
          <td style="padding: 2px;text-align: right;"><span style="font-size: 15px;"><?php echo  number_format((float)$sumAmount,2,'.',','); ?></span></td>
        
      </tr>
      <tr>
          <td style="padding: 2px;width: 600px;"><span style="font-size: 15px;">Freight Charge :</span></td>
          <td style="padding: 2px;text-align: right;"><span style="font-size: 15px;"><?php echo  number_format((float)$sumShipping,2,'.',','); ?></span></td>
        
      </tr>
      <tr>
          <td style="padding: 2px;width: 600px;"><span style="font-size: 15px;">Commissioning,Installation, & Training Charge (C.I.T) :</span></td>
          <td style="padding: 2px;text-align: right;"><span style="font-size: 15px;"><?php echo  number_format((float)$sumInstall,2,'.',','); ?></span></td>
        
      </tr>
      <?php 
             $total = $sumAmount + $sumShipping + $sumInstall;

             $deductGst = $total * ($list[0]['sellers'][0]['tax'] / 100 );

              
      ?>
      <tr>
          <td style="padding: 2px;width: 600px;">
            <span style="font-size: 15px;">

              <?= $list[0]['sellers'][0]['tax']; ?>% 
              <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?>


            </span>
          </td>
          <td style="padding: 2px;text-align: right;"><span style="font-size: 15px;"><?php  echo number_format((float)$deductGst,2,'.',','); ?></span></td>
        
      </tr>
  </table>
  <br>
  <table width="100%" border="0">
      <tr>
          <td style="padding: 2px;width: 600px;">
            <span style="font-size: 25px;">

              <b>Total</b> (<?= $list[0]['sellers'][0]['tax']; ?>% <?= empty($list[0]['sellers'][0]['type_of_tax']) ? '(Empty Type Of Tax)' : $list[0]['sellers'][0]['type_of_tax'] ; ?>) : 


            </span>
          </td>
          <td style="padding: 2px;text-align: right;"><span style="font-size: 25px;font-weight: bold;">
<?php

    $grandTotal = $total + $deductGst;
    echo number_format((float)$grandTotal,2,'.',','); 

 ?>
          </span></td>
      </tr>
  </table>


</div>