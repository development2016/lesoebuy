<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeliveryAddress */

$this->title = 'Update Delivery Address: ' . $model->warehouse_name;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Address', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->warehouse_name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6>

					    <?= $this->render('_form', [
					        'model' => $model,
					    ]) ?>
			</div>
		</div>
	</div>
</div>
