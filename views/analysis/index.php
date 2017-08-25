<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
$this->title = 'Analysis';
$this->params['breadcrumbs'][] = $this->title;


$script = <<< JS
$(document).ready(function(){


    $('#generate').on('click', function () {

    	var status = $('#status').val();
        $.ajax({
            type: 'POST',
            url: 'report',
            data: 'status='+status,
            success: function(data) {
            	$(".info-complete").show();
                $(".data").html(data);
   

            }
        })


    });




}); 
JS;
$this->registerJs($script);

//data: 'level='+level+'&buyer='+buyer+'&project='+project+'&seller='+seller+'&type='+type,

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
       
                <h4 class="card-title">Filter</h4>
                <h6 class="card-subtitle">Description About Panel</h6>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control status" id="status" >
                        	<option value="">-Please Choose-</option>
                            <option value="PO Completed">PO Completed</option>
                            <option value="PR Cancel">PR Cancel</option>
                            <option value="Approve">Approve</option>
                            <option value="Reject PR">Reject PR</option>
                            <option value="PO In Progress">PO In Progress</option>
                            <option value="Request Approval">Request Approval</option>
              
                        </select>
                    </div>
                </div>


                <button type="button" class="btn btn-info pull-right" id="generate">GENERATE </button>


 
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

		        <div class="data">
                <div class="table-responsive">




                </div>
	



		        </div>

		    </div>
		</div>

    </div>
</div>

