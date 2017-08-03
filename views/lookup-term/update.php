<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LookupTerm */

$this->title = 'Update Lookup Term: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lookup Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lookup-term-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
