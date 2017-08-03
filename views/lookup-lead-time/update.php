<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LookupLeadTime */

$this->title = 'Update Lookup Lead Time: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lookup Lead Times', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lookup-lead-time-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
