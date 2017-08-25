<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Supplier List', 'url' => ['index']];
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
                        'company_name',
                        'company_registeration_no',
                        'address',
                        'zip_code',
                        'countrys.country',
                        'states.state',
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
        </div>
    </div>
</div>


