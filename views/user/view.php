<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Account Info : '.$model->account_name;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(Yii::$app->session->hasFlash('update')) { ?>
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert"></button>
         <?php echo  Yii::$app->session->getFlash('update'); ?>
    </div>
<?php } ?>

<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-block">
                <center class="m-t-30"> 
                <?php if (empty($model->img)) { ?>

                    <img src="<?php echo Yii::$app->request->baseUrl; ?>/image/leso/leso.png" class="img-circle" width="150" />
                <?php } else { ?>
                    <img src="<?php echo Yii::$app->request->baseUrl; ?>/image/<?= $model->img ?>" class="img-circle" width="150" />
                <?php } ?>


                
                    <h4 class="card-title m-t-10"><?= $model->name ?></h4>
                    <h6 class="card-subtitle">
                    <?php foreach ($role as $key => $value) { ?>
                        <?php echo $value['role']; ?> &#8226;

                    <?php } ?>


                    </h6>

                </center>
            </div>
            <div>
                <hr> </div>
            <div class="card-block"> <small class="text-muted">Email address </small>
                <h6><?= $model->email ?></h6> 
                <small class="text-muted p-t-30 db">Account Name</small>
                <h6><?= $model->account_name ?></h6> 



            </div>
        </div>
    </div>

    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <div class="card-block">

                <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
                <h6 class="card-subtitle">Description About Panel</h6> 


                    <?= $this->render('_edit', [
                        'model' => $model,
                    ]) ?>


            </div>
        </div>
    </div>





</div>
