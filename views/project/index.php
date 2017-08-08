<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Project';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
          

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'project_no',
                        [
                            'label' => 'Information',
                            'attribute' => 'details',
                            'format'=>'raw',
                            'value'=>function ($data) {

                                return '<span><b>PR No : </b>'.'<a class="mytooltip" href="#">'.$data->sellers[0]['purchase_requisition_no'].'</a>'.'</span><br>
                                    <span><b>Status : </b>'.$data->sellers[0]['status'].'</span><br><br>
                                    <span><b>Date Create : </b>'.$data->date_create.'</span>';



      
                            }
                        ],
                        [
                            'label' => 'Details',
                            'attribute' => 'details',
                            'format'=>'raw',
                            'value'=>function ($data) {

                                return '<span><b>Title : </b>'.$data->title.'</span><br>
                                <span><b>Description : </b>'.$data->description.'</span><br>
                                <span><b>Due Date : </b>'.$data->due_date.'</span>';



      
                            }
                        ],






                        ['class' => 'yii\grid\ActionColumn'],
                    ],
        'tableOptions' => [
            'class' => 'table table-hover',
        ],

                ]); ?>

            </div>
        </div>
    </div>
</div>
