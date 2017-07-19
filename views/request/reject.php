<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Project No : '.$model[0]['project_no'];
$this->title = 'Reject PR';

?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>
<br>
<span>
<?php $form = ActiveForm::begin(); ?>

	<div class="form-group">

	<?= $form->field($model, 'sellers[pr_reject][remark]')->textarea(['rows' => 6,'id'=>'remark'])->label('Remark') ?>

	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>



<?php ActiveForm::end(); ?>
</span>



	
</div>