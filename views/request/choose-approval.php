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
$this->title = 'Choose Approval';

?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>
<br>
<span>
<?php $form = ActiveForm::begin(); ?>

	<div class="form-group">

		<ul class="list-group">
			<?php foreach ($approval as $key => $value) { ?>
		    	<li class="list-group-item justify-content-between">
		    		<?php echo $value['account_name'] ?>


					<label class="custom-control custom-checkbox ">
					  <input type="checkbox" class="custom-control-input " value="<?php echo $value['account_name'] ?>" name="Project[sellers][approval][]">
					  <span class="custom-control-indicator"></span>

					</label>


		        </li>
		    <?php } ?>

		</ul>


	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Choose' : 'Choose', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>



<?php ActiveForm::end(); ?>
</span>



	
</div>