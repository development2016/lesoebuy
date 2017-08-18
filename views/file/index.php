<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
$this->title = 'File';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('.uploads').click(function(){
        $('#modalmd').modal('show').find('#modalContentMd').load($(this).attr('value'));

    });

}); 
JS;
$this->registerJs($script);


?>
<?php if(Yii::$app->session->hasFlash('remove_file')) { ?>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert"></button>
         <?php echo  Yii::$app->session->getFlash('remove_file'); ?>
    </div>
<?php } ?>

<?php if(Yii::$app->session->hasFlash('uploaded')) { ?>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert"></button>
         <?php echo  Yii::$app->session->getFlash('uploaded'); ?>
    </div>
<?php } ?>



<div class="row">
  	<div class="col-md-12">
	    <div class="card">
	        <div class="card-block">

	        	<?= Html::a('UPLOAD FILE',FALSE, ['value'=>Url::to(['file/upload',
	        	'project' => (string)$newProject_id,
	        	'project_no' => $project_no,

	        	]),'class' => 'btn btn-sm btn-info uploads pull-right','id'=>'','title'=>'Upload File','style'=>'color: #fff;cursor:pointer;text-decoration:none;']) ?>
	   
	            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
	            <h6 class="card-subtitle">Description About Panel</h6>

	            <?php if (empty($model)) { ?>

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
					    		<?php $i=0; foreach ($model[0]['filename'] as $key => $value) { $i++;?>
					    			<tr>
					    				<td>
								       		<?php if ($value['ext'] == 'pdf') { ?>
								       			<i class="mdi mdi-file-pdf-box" style="color: red;font-size:30px;"></i>
								       		<?php } elseif ($value['ext'] == 'xlsx') { ?>
								       			<i class="mdi mdi-file-excel-box" style="color: green;font-size:30px;"></i>
								       		<?php } elseif ($value['ext'] == 'docx') { ?>
								       			<i class="mdi mdi-file-word-box" style="color: blue;font-size:30px;"></i>
								       		<?php } else { ?>
								       			<i class="mdi mdi-file"></i>
								       		<?php } ?>

					    				<?php echo $value['file']; ?></td>
					    				<td><?php echo $value['date_create']; ?></td>
					    				<td>
					    					<?= Html::a('View', ['view', 
						       				'path' => $value['path'],
						       				'extension' => $value['ext'],
						       				], ['class' => 'btn btn-warning btn-sm','title'=>'View Upload File','target' => '_BLANK']) ?>

						       				<?= Html::a('Delete', ['delete', 
						       				'project' => (string)$newProject_id,
						       				'path' => $value['path'],
						       				], ['class' => 'btn btn-danger btn-sm','title'=>'Delete File']) ?>



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