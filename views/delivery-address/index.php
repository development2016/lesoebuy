<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Address';
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

            'warehouse_name',
             [
                'label' => 'Address',
                'attribute' => 'address',
                'value' => function ($data){
                        return $data->address.','.$data->postcode.','.$data->location;

                },
             ],
            'contact',
            'fax',
            'email',
            // 'latitude',
            // 'longitude',
            [
                'header' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}  {edit}  {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('View', 
                                    $url,['title'=> 'Lihat','class'=>'btn btn-secondary']);

                        },
                        'edit' => function ($url, $model) {
                            return Html::a('Edit', $url, [
                                        'title' => 'Kemaskini',
                                        'class'=>'btn btn-warning'
                            ]);
                        },

                        'delete' => function ($url, $model) {
                            return Html::a('Delete', $url, [
                                        'title' => 'Buang',
                                        'class'=>'btn waves-effect waves-light btn-danger',
                                        'data-method' => 'POST'
                            ]);

                        },

                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'view') {
                            $url = ['delivery-address/view','id'=>(string)$model->_id];
                            return $url;
                        }
                        if ($action === 'edit') {
                            $url = ['delivery-address/update','id'=>(string)$model->_id];
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url = ['delivery-address/delete','id'=>(string)$model->_id];
                            return $url;
                        }
                    }
            ],
     
        ],
        'tableOptions' => [
            'class' => 'table table-hover',
        ],
    ]); ?>



            </div>
        </div>
    </div>
</div>
