<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DeliveryAddress */

$this->title = $model->warehouse_name;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Addresses', 'url' => ['index']];
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
                        'warehouse_name',
                        'address',
                        'countrys.country',
                        'states.state',
                        'location',
                        'latitude',
                        'longitude',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>
