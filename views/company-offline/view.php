<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Supplier List', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-offline-view">

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
            'company_name',
            'company_registeration_no',
            'address',
            'zip_code',
            'country',
            'state',
            'city',
            'telephone_no',
            'fax_no',
            'email',
            'website',
            'tax',
            'type_of_tax',
            'warehouses',
            'term',
        ],
    ]) ?>

</div>
