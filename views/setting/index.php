<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = 'Setting';
$this->params['breadcrumbs'][] = $this->title;




$script = <<< JS
$(document).ready(function(){

    $('.leadtime').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.term').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });
    
}); 
JS;
$this->registerJs($script);
?>

    <?php if(Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert"></button>
             <?php echo  Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php } ?>


<div class="row">
  	<div class="col-md-12">
      	<div class="card">
	        <div class="card-body">

                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        LOOKUP
                    </button>
                    <div class="dropdown-menu animated flipInX">
                        <?= Html::a('Lead Time',FALSE, ['value'=>Url::to([
                            'lookup-lead-time/create',
 
                            ]),'class' => 'dropdown-item leadtime','id'=>'','title' => 'This To Add New Lead Time']) ?>

                        <?= Html::a('Term',FALSE, ['value'=>Url::to([
                            'lookup-term/create',

                            ]),'class' => 'dropdown-item term','id'=>'','title' => 'This To Add New Term']) ?>



                    </div>
                </div>




	            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
	            <h6 class="card-subtitle">Description About Panel</h6> 

	        </div>
	        <!-- Nav tabs -->
	        <ul class="nav nav-tabs customtab" role="tablist">
	            <li class="nav-item"> 
	              <a class="nav-link active" data-toggle="tab" href="#lead" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Lead Time</span></a> 
	            </li>
	            <li class="nav-item"> 
	              <a class="nav-link" data-toggle="tab" href="#term" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Term</span></a> 
	            </li>

	        </ul>
	              <!-- Tab panes -->
	        <div class="tab-content">
	            <div class="tab-pane active" id="lead" role="tabpanel">
	                <div class="p-20 ">


					<?= GridView::widget([
					        'dataProvider' => $dataProviderLeadTime,
					        'columns' => [
					            ['class' => 'yii\grid\SerialColumn'],
					            'lead_time',


				            [
				                'header' => 'Action',
				                'class' => 'yii\grid\ActionColumn',
				                'template'=>'{edit} {delete}',
				                    'buttons' => [

				                        'edit' => function ($url, $model) {
				                            return Html::a('Edit', $url, [
				                            			'value' => Url::to(['lookup-lead-time/update','id'=>$model->id]),
				                                        'title' => 'Edit',
				                                        'class'=>'btn btn-warning leadtime',
				                                        'style' => 'color:#fff;'
				                            ]);
				                        },

				                        'delete' => function ($url, $model) {
				                            return Html::a('Delete', FALSE, [
				                                        'title' => 'Delete',
				                                        'class'=>'btn waves-effect waves-light btn-danger',
				                                        'style' => 'color:#fff;',
				                                        'data-method' => 'POST'
				                            ]);

				                        },

				                    ],
				                    'urlCreator' => function ($action, $model, $key, $index) {
				                        if ($action === 'edit') {
				                            $url = FALSE;
				                            return $url;
				                        }
				                        if ($action === 'delete') {
				                            $url = ['lookup-lead-time/delete','id'=>$model->id];
				                            return $url;
				                        }
				                    }
				            ],



					        ],


					        'tableOptions' =>[
                            'class' => 'table table-hover',
                        	],
					    ]); ?>
		



	                </div>
	            </div>
	            <div class="tab-pane" id="term" role="tabpanel">
	                <div class="p-20 ">


	  
					<?= GridView::widget([
					        'dataProvider' => $dataProviderTerm,

					        'columns' => [
					            ['class' => 'yii\grid\SerialColumn'],
					            'term',


					        [
				                'header' => 'Action',
				                'class' => 'yii\grid\ActionColumn',
				                'template'=>'{edit} {delete}',
				                    'buttons' => [

				                        'edit' => function ($url, $model) {
				                            return Html::a('Edit', $url, [
				                            			'value' => Url::to(['lookup-term/update','id'=>$model->id]),
				                                        'title' => 'Edit',
				                                        'class'=>'btn btn-warning term',
				                                        'style' => 'color:#fff;'
				                            ]);
				                        },

				                        'delete' => function ($url, $model) {
				                            return Html::a('Delete', $url, [
				                                        'title' => 'Buang',
				                                        'class'=>'btn waves-effect waves-light btn-danger',
				                                        'style' => 'color:#fff;',
				                                        'data-method' => 'POST'
				                            ]);

				                        },

				                    ],
				                    'urlCreator' => function ($action, $model, $key, $index) {
				                        if ($action === 'edit') {
				                        	$url = FALSE;
				                            return $url;
				                        }
				                        if ($action === 'delete') {
				                            $url = ['lookup-term/delete','id'=>$model->id];
				                            return $url;
				                        }
				                    }
				            ],



					        ],



					        'tableOptions' =>[
                            'class' => 'table table-hover',
                        ],
					    ]); ?>





	                </div>
	            </div>



	        </div>

	    </div>
	</div>
</div>
