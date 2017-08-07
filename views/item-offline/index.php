<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemOfflineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-info pull-right']) ?>
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6>

<?php Pjax::begin(); ?>  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_code',
            'item_name',
            'brand',
            'model',
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
                            $url = ['item-offline/view','id'=>(string)$model->_id];
                            return $url;
                        }
                        if ($action === 'edit') {
                            $url = ['item-offline/update','id'=>(string)$model->_id];
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url = ['item-offline/delete','id'=>(string)$model->_id];
                            return $url;
                        }
                    }
            ],
     
        ],
        'tableOptions' => [
            'class' => 'table table-hover',
        ],
        
    ]); ?>
<?php Pjax::end(); ?>


            </div>
        </div>
    </div>
</div>
