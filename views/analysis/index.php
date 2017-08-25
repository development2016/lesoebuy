<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['modules/data', 'modules/drilldown']);
$this->title = 'Analysis';
$this->params['breadcrumbs'][] = $this->title;


$script = <<< JS
$(document).ready(function(){


    $('#generate-status').on('click', function () {

    	var status = $('#status').val();
        var buyer = $('#buyer').val();
        $.ajax({
            type: 'POST',
            url: 'report-status',
            data: 'status='+status,
            data: 'status='+status+'&buyer='+buyer,
            success: function(data) {
            	$(".info-complete").show();
                $(".data").html(data);

            }
        });

    });


    $('#startDate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#endDate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });



}); 
JS;
$this->registerJs($script);


$month = [
    'January' => 'January',
    'February' => 'February',
    'March' => 'March',
    'April' => 'April',
    'May' => 'May',
    'June' => 'June',
    'July' => 'July',
    'August' => 'August',
    'September' => 'September',
    'October' => 'October',
    'November' => 'November',
    'December' => 'December'
]


//data: 'level='+level+'&buyer='+buyer+'&project='+project+'&seller='+seller+'&type='+type,

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
       
                <h4 class="card-title">Filter</h4>
                <h6 class="card-subtitle">Description About Panel</h6>


                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Type</label>
                                    <select class="form-control status" id="status" >
                                        <option value="">All</option>
                                        <option value="PO Completed">Completed PO</option>
                                        <option value="PO In Progress">PO In Progress</option>
                                        
                          
                                    </select>
                                </div>
                            </div>

                           <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Supplier</label>
                                    <select class="form-control status" id="status" >
                                        <option value="">All</option>
                                        <?php 

                                        foreach ($supplier as $key_supplier => $value_supplier) { ?>
                                            <option value="<?= $value_supplier['company_name']; ?>"><?= $value_supplier['company_name'];?></option>
                                        <?php }


                                        ?>
                                        
                          
                                    </select>
                                </div>
                            </div>




                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Buyer</label>
                                    <select class="form-control buyer" id="buyer" >
                                    <option value="">All</option>
                                    <?php 

                                        foreach ($buyer as $key => $value) { ?>
                                            <option value="<?= $value['account_name']; ?>"><?= $value['account_name'];?></option>
                                        <?php }


                                    ?>
                                     
                                        
                          
                                    </select>
                                </div>
                            </div>




                            
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-info pull-right" id="generate-status">GENERATE </button>
                            </div>
                        </div>

                   
            </div>


 
            
        </div>
    </div>
</div>




<div class="row info-complete" style="display: none;">
	<div class="col-md-12">
	    <div class="card">
            <div class="card-body">

                <h4 class="card-title">Report</h4>
                <h6 class="card-subtitle">Description About Panel</h6>
               
            

                <div class="data table-responsive">




                </div>
	



		       

		    </div>
		</div>

    </div>
 



</div>

