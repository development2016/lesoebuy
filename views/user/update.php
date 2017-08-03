<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . $model->account_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
         

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 


	            <div class="row">

	            	<div class="col-lg-12">
		    
						<?php if(Yii::$app->session->hasFlash('update')) { ?>
						    <div class="alert alert-info">
						        <button type="button" class="close" data-dismiss="alert"></button>
						         <?php echo  Yii::$app->session->getFlash('update'); ?>
						    </div>
						<?php } ?>

				    <?= $this->render('_edit', [
				        'model' => $model,
				    ]) ?>

				    </div>

				</div>

			</div>
		</div>
	</div>
</div>
