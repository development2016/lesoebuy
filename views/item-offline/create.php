<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = 'Create Item';
$this->params['breadcrumbs'][] = ['label' => 'Item Offlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
