<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Temp';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-hover">
        <tr>
            <th>No</th>
            <th>Project No</th>
            <th>PO Date</th>
            <th>PO Year</th>
            <th>PO Month</th>
            <th>PO Days</th>
        </tr>

    <?php $i=0; foreach ($list as $key => $value) { $i++;?>

    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $value['project_no']; ?></td>
        <td><?php echo $value['sellers'][0]['date_purchase_order'][0]; ?></td>
        <td><?php echo $value['year_po']; ?></td>
        <td><?php echo $value['month_po']; ?></td>
        <td><?php echo $value['day_po']; ?></td>
        <td>
            <?= Html::a('Edit', ['update','id'=>(string)$value['_id']], ['class' => 'btn btn-info']) ?>
        </td>
    </tr>

    <?php } ?>
        
    </table>



</div>
