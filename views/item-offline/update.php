<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = 'Update Item : ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Item Offlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-offline-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
