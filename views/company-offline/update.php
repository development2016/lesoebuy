<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */

$this->title = 'Update Supplier: ' . $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Supplier List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->company_name, 'url' => ['view', 'id' => (string)$model->_id]];
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
