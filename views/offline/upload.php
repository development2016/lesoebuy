<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$script = <<< JS
$(document).ready(function(){


}); 
JS;
$this->registerJs($script);



$this->title = 'Upload File';

?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>


	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

	    <?= $form->field($model, 'file')->fileInput() ?>

	    <button class="btn btn-primary" id="myButton" data-loading-text="Loading..." >Upload</button>


	<?php ActiveForm::end() ?>

</div>
