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
$this->title = 'Choose Approver';
$script = <<< JS
$(document).ready(function(){

	$('.btn-app:checked').each(function() {
        $("#btn-submit").attr('disabled',false);
    });

    //This will check the status of radio button onclick
    $('.btn-app').click(function() {
        $("#btn-submit").attr('disabled',false);
    });

}); 
JS;
$this->registerJs($script);

?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>

<?php $form = ActiveForm::begin(); ?>

	<div class="form-group">

		<ul class="list-group">
			<?php foreach ($approval as $key => $value) { ?>
		    	<li class="list-group-item justify-content-between">
		    		<?php echo $value['account_name'] ?>

		    		<input name="Project[sellers][approval][]" type="radio" id="<?php echo $value['account_name'] ?>" class="radio-col-light-green btn-app" value="<?php echo $value['account_name'] ?>" />
                    <label for="<?php echo $value['account_name'] ?>"></label>


		        </li>
		    <?php } ?>

		</ul>


	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Choose' : 'Choose', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info','id'=>'btn-submit', 'disabled' => 'disabled']) ?>
    </div>

<?php ActiveForm::end(); ?>



	
</div>