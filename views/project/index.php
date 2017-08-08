<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Project';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#list').DataTable();


}); 
JS;
$this->registerJs($script);



?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
          

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 


                <table class="table table-hover" id="list">
                    <thead >
                        <tr>
                            <th>No</th>
                            <th>Project No</th>
                            <th>Details</th>
                            <th>Information</th>
                            <th>Approver Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; foreach ($model as $key => $value) { $i++;?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $value['project_no'] ?></td>
                            <td>
                                <span><b>Title : </b><?= $value['title'] ?></span>
                                <br>
                                <span><b>Description : </b><?= $value['description'] ?></span>
                                <br>
                                <span><b>Due Date : </b><?= $value['due_date'] ?></span>
                            </td>
                            <td>
                                <span><b>PR No : </b><a class="mytooltip" href="#"><?= $value['sellers']['purchase_requisition_no'] ?></a></span><br>
                                <span><b>Status : </b><?= $value['sellers']['status'] ?></span>
                                <br>
                                <br>
                                <span><b>Buyer </b>
                                <ul>
                                    <?php foreach ($value['buyers'] as $key_buyer => $value_buyer) { ?>
                                    <li><?= $value_buyer['buyer']; ?></li>
                                    <?php } ?>
                                </ul>


                                </span>
                            </td>
                            <td>
                                <span><b>Approver </b>

                                <?php if (empty($value['sellers']['approval'])) { ?>
                                    <ul>
                                        <li>
                                            <span>Not Set Yet</span>
                                        </li>
                                    </ul>
                                <?php } else { ?>

                                <ul>
                                    <?php foreach ($value['sellers']['approval'] as $key_approval => $value_approval) { ?>
                                    <li><?= $value_approval['approval']; ?> : 
                                    <?php if (empty($value_approval['status'])) { ?>
                                        
                                    <?php } else { ?>
                                        <?= $value_approval['status']; ?>
                                    <?php } ?>

                                    </li>
                                    <?php } ?>
                                </ul>

                                <?php } ?>





                                </span>
                            </td>
                            <td>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Purchase Requisition
                                    </button>
                                    <div class="dropdown-menu animated flipInX">
                                        
                                          <?= Html::a('<b>'.$value['sellers']['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                        'project'=>(string)$value['_id'],
                                                        'seller'=>$value['sellers']['seller'],
                                                        'buyer'=>$value['buyers'][0]['buyer'],
                                                        ],['target'=>'_blank','class'=>'dropdown-item']) ?>
                                        
                                    </div>
                                </div>


                                <?= Html::a('File', ['file/index',
                                'project'=>(string)$value['_id'],
                                ],['class'=>'btn btn-secondary','title'=>'File']) ?>
                            </td>
                        <?php } ?>
                        </tr>
                    </tbody>
                </table>





            </div>
        </div>
    </div>
</div>
