<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->account_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
         
            <?= Html::a('Update Info', ['update', 'id' => $model->id], ['class' => 'btn btn-info pull-right']) ?>
                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 


                <div class="row">

                    <div class="col-lg-12">
            


                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'name',
                                'username',
                                'email:email',
                                'account_name',
                            ],
                        ]) ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
