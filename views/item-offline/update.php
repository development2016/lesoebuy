<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = 'Update Item : ' . $model->item_code;
$this->params['breadcrumbs'][] = ['label' => 'Item List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_code, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-offline-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
