<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItemOffline */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Item List', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-info pull-right']) ?>
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_code',
            'item_name',
            'brand',
            'model',
            'specification',
            'leads.lead_time',
            //'validity',
            'cost',
            'quantity',
            'cit',
            'shipping',
            'remark',
        ],
    ]) ?>
            </div>
        </div>
    </div>
</div>


