<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = 'Update Item : ' . $model->item_code;
$this->params['breadcrumbs'][] = ['label' => 'Item List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_code, 'url' => ['view', 'id' => (string)$model->_id]];
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