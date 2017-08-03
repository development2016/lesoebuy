<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Item Offlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-offline-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_code',
            'item_name',
            'brand',
            'model',
            'specification',
            'lead_time',
            'validity',
            'cost',
            'quantity',
            'cit',
            'shipping',
            'remark',
        ],
    ]) ?>

</div>
