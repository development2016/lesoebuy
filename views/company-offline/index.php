<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Offlines';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-info pull-right']) ?>
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'company_name',
            'company_registeration_no',
            'address',
            'zip_code',
            // 'country',
            // 'state',
            // 'city',
            // 'telephone_no',
            // 'fax_no',
            // 'email',
            // 'website',
            // 'tax',
            // 'type_of_tax',
            // 'warehouses',
            // 'date_create',
            // 'enter_by',
            // 'date_update',
            // 'update_by',
            // 'term',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>



            </div>
        </div>
    </div>
</div>

