<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'List All';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
  	<div class="col-md-12">
      	<div class="card">
	        <div class="card-block">

	            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
	            <h6 class="card-subtitle">Description About Panel</h6>

	        </div>

            <div class="table-responsive m-t-40">
                  
                <table class="table" id="example">
                    <thead >
                        <tr>
                            <th>No</th>
                            <th>Project No</th>
                            <th>PR</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=0; foreach ($model as $key => $value) { $i++;?>
                    <tr>
                    	<td><?= $i ?></td>
                    	<td><?= $value['project_no'] ?></td>
                    	<td>

                    	<?php if ($value['status_approver'] == 'Pending') { ?>

                            <?= Html::a($value['details'], [$value['url'],
                            'project' => (string)$value['project_id'],
                            'seller' => $value['seller'],
                            'buyer' => $value['from_who'],
                            'approver' => $value['approver'],


                            ],
                            ['class'=>'','title'=>'PR']) ?>

                            
                    	<?php } elseif ($value['status_approver'] == 'Noted') { ?>

                            <?= Html::a($value['details'], [$value['url'],

                            ],
                            ['class'=>'','title'=>'PR']) ?>



                    	<?php } ?>

                    	
                    		
                    	</td>
                    </tr>


                    <?php } ?>
                    </tbody>
                </table>
            </div>

	        
	    </div>
	</div>
</div>



