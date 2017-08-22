<?php

use yii\helpers\Html;
use meysampg\gmap\GMapMarker;
use yii\helpers\Url;
use app\models\LookupCountry;
use app\models\LookupState;
/* @var $this yii\web\View */
/* @var $model app\models\CompanyInformation */

$this->title = strtoupper('Manage Branch');

$script = <<< JS
$(document).ready(function(){

    $('.warehouse-add').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

}); 
JS;
$this->registerJs($script);




?>

<div class="row">
  <div class="col-md-6">
      <div class="card">
        <div class="card-block">
   
        <?= Html::a('Add Branch',FALSE, ['value'=>Url::to(['company/warehouse','company_id'=>(string)$newCompanyid]),'class' => 'btn btn-info pull-right warehouse-add','id'=>'create','style'=>'color:#fff;']) ?>
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            <h6 class="card-subtitle">Description About Panel</h6> 



        <?php if (empty($process)) { ?>
   
        <?php } else { ?>


            <?php $i=0; foreach ($process[0]['warehouses'] as $key => $value) { $i++; 

            $country = LookupCountry::find()->where(['id'=>$value['country']])->one();
            $state = LookupState::find()->where(['id'=>$value['state']])->one();

            ?>

                
                        <h4><b><?php echo $country->country; ?> / <?php echo $state->state; ?> / <?php echo $value['location']; ?></b></h4>
                        <?= GMapMarker::widget([
                            'width' => '300px', // Using pure number for 98% of width.
                            'height' => '200px', // Or use number with unit (In this case 400px for height).
                            'marks' => [$value['latitude'], $value['longitude']],
                            'zoom' => 10,
                            'disableDefaultUI' => true
                        ]); ?>
                        <br>
                        <table class="table table-bordered">
                          <tr>
                            <th>PIC</th>
                            <td><?php echo $value['person_in_charge']; ?></td>
                          </tr>
                          <tr>
                            <th>Contact</th>
                            <td><?php echo $value['contact']; ?></td>
                          </tr>
                          <tr>
                            <th>Email</th>
                            <td><?php echo $value['email']; ?></td>
                          </tr>


                          <tr>
                            <th>Location</th>
                            <td><?php echo $value['address']; ?>,<?php echo $value['location']; ?>,<?php echo $state->state; ?></td>
                          </tr>

                        </table>

            <?php } ?>



        <?php } ?>



        </div>





      </div>
  </div>
  <div class="col-md-6">
      <div class="card">
            <div class="card-block">

            <h4 class="card-title">Branch</h4>
            <h6 class="card-subtitle">Description About Panel</h6> 


        <?php if (empty($process)) { ?>
   
        <?php } else { ?>


            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Person In Charge</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <?php $i=0; foreach ($process[0]['warehouses'] as $key => $value) { $i++; ?>
                <tbody>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $value['person_in_charge']; ?></td>
                        <td><?php echo $value['contact']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                        <td><?php echo $value['address']; ?> , <?php echo $value['location']; ?> , <?php echo $state->state; ?> , <?php echo $country->country; ?>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>

            </table>



        <?php } ?>



            </div>
        </div>
    </div>



</div>