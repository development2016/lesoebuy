<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\LookupCountry;
use app\models\LookupState;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\LookupTerm;

$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');

$type_of_tax = ['VAT'=>'VAT','GST'=>'GST'];

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$script = <<< JS
$(document).ready(function(){

	$('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('.uploads').click(function(){
        $('#modalmd').modal('show').find('#modalContentMd').load($(this).attr('value'));

    });

    $('.companyoff').on('click', function () {
        var companyname = $(this).data('companyname');
        var registrationno = $(this).data('registrationno');
        var address = $(this).data('address');
        var zipcode = $(this).data('zipcode');
        var country = $(this).data('country');
        var state = $(this).data('state');

        var city = $(this).data('city');
        var telephoneno = $(this).data('telephoneno');
        var faxno = $(this).data('faxno');
        var email = $(this).data('email');
        var website = $(this).data('website');
        var typeoftax = $(this).data('typeoftax');
        var tax = $(this).data('tax');
        var term = $(this).data('term');

        $('#companyname').val(companyname);
        $('#registrationno').val(registrationno);
        $('#address').val(address);
        $('#zipcode').val(zipcode);
        $('#country').val(country);
        $('#state').val(state);

        $('#city').val(city);
        $('#telephoneno').val(telephoneno);
        $('#faxno').val(faxno);
        $('#email').val(email);
        $('#website').val(website);
        $('#typeoftax').val(typeoftax);
        $('#tax').val(tax);
        $('#term').val(term);


    });




}); 
JS;
$this->registerJs($script);


$this->title = 'Project No : '.$project;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
  	<div class="col-md-9">
      	<div class="card">
        	<div class="card-block">

            	<h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            	<h6 class="card-subtitle">Description About Panel</h6> 

            		<?php $form = ActiveForm::begin(); ?>
                    <div id="accordion" class="nav-accordion" role="tablist" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                                <h5 class="mb-0">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Project
                            </a>
                          </h5> </div>
                            <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                <div class="card-block"> 

							    <?= $form->field($model3, 'title')->textInput(['id'=>'id-title','placeholder'=>'-Buy What From Who For Who-']) ?>

							    <?= $form->field($model3, 'description')->textarea(['rows' => 6]) ?>

							    <?= $form->field($model3, 'due_date')->textInput(['id'=>'mdate']) ?>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="headingTwo">
                                <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              File
                            </a>
                          </h5> </div>
                            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="card-block"> 

							<?php if (empty($model2)) { ?>

						    	<div class="alert alert-danger" role="alert"><strong>Empty : </strong> No File Uploaded </div>

						    <?php } else { ?>

						    	<div class="alert alert-success" role="alert">You Have Upload <strong><?= count($model2) ?></strong> File For This Project</div>

						    <?php } ?>

							<div class="pull-right">
				              <?= Html::a('UPLOAD FILE',FALSE, ['value'=>Url::to(['offline/upload','project' => $project]),'class' => 'btn btn-info uploads','id'=>'','title'=>'Upload Image','style'=>'color: #fff;cursor:pointer;text-decoration:none;']) ?>

				            </div>


						    <table class="table table-hover">
						    	<thead>
							    	<tr>
							        	<th>Filename</th>
							        	<th>Path</th>
							        	<th>Date Create</th>
							        	<th>Action</th>
							        </tr>
						    	</thead>
						    	<tbody>
						       	<?php foreach ($model2 as $key => $value) { ?>
						       	<tr>
						       		<td><?= $value['filename']; ?></td>
						       		<td><?= $value['path']; ?></td>
						       		<td><?= $value['date_create']; ?></td>
						       		<td>
						   
						       			<?= Html::a('View', ['view', 
						       				'id' => $value->id,
						       				'filename' => $value->filename,
						       				'project' => $project,
						       				], ['class' => 'btn btn-warning btn-sm','title'=>'View Upload File']) ?>

						       		

						       		
						       			<?= Html::a('Delete', ['delete', 
						       				'id' => $value->id,
						       				'filename' => $value->filename,
						       				'path'=>$value->path,
						       				'company_id' => $value->company_id,
						       				'project' => $project,
						       				], ['class' => 'btn btn-danger btn-sm','title'=>'Remove Upload File']) ?>
						  
						       		</td>
						       	</tr>

						        <?php } ?>
						        </tbody>
						    </table>

					       	<?php foreach ($model2 as $key => $value) { ?>

					       	<?= $form->field($model3, 'document[filename][]')->hiddenInput(['value'=>$value['filename']])->label(false) ?>
					       	<?= $form->field($model3, 'document[path][]')->hiddenInput(['value'=>$value['path']])->label(false) ?>
					       	<?= $form->field($model3, 'document[company_id][]')->hiddenInput(['value'=>$value['company_id']])->label(false) ?>
					       	<?= $form->field($model3, 'document[date_create][]')->hiddenInput(['value'=>$value['date_create']])->label(false) ?>


					        <?php } ?>




                                 </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="headingThree">
                                <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              Supplier Info
                            </a>
                          </h5> </div>
                            <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="card-block">
                                	
								<?= $form->field($model, 'company_name')->textInput(['id'=>'companyname']) ?>

							    <?= $form->field($model, 'company_registeration_no')->textInput(['id'=>'registrationno']) ?>

								<?= $form->field($model, 'address')->textarea(['rows' => 6,'id' => 'address']) ?>

							    <?= $form->field($model, 'zip_code')->textInput(['id'=>'zipcode']) ?>

							    <?= $form->field($model, 'country')->dropDownList(
							        $country, 
							    [
							        'prompt' => '-Select Country-',
							        'class' => 'form-control',
							        'id' => 'country',
							        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/offline/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',
							        //'options' => ['3' => ['selected'=>true]]

							    ]) ?>

							    <?= $form->field($model, 'state')->dropDownList(
							        $state, 
							    [
							        'prompt' => '-Select State-',
							        'class' => 'form-control',
							        'id'=> 'state',

							    ]) ?>

							    <?= $form->field($model, 'city')->textInput(['id'=>'city']) ?>

							    <?= $form->field($model, 'telephone_no')->textInput(['id'=>'telephoneno']) ?>

							    <?= $form->field($model, 'fax_no')->textInput(['id'=>'faxno']) ?>

							    <?= $form->field($model, 'email')->textInput(['id'=>'email']) ?>

							    <?= $form->field($model, 'website')->textInput(['id'=>'website']) ?>

				                <?= $form->field($model, 'type_of_tax')->dropDownList(
				                    $type_of_tax, 
				                [
				                    'prompt' => '-Select Type Of Tax',
				                    'class' => 'form-control',
				                    'id' => 'typeoftax',
				                    'options' => ['GST' => ['selected'=>true]]

				                ]) ?>

							    <?= $form->field($model, 'tax')->textInput(['value'=>'6','id'=>'tax']) ?>

				                <?= $form->field($model, 'term')->dropDownList(
				                    $term, 
				                [
				                    'prompt' => '-Select Term-',
				                    'class' => 'form-control',
				                    'id' => 'term'

				                ]) ?>


							    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', [
							    'class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info',

							    ]) ?>


                                	
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>



        	</div>
       	</div>
    </div>


  	<div class="col-md-3">
      	<div class="card">
        	<div class="card-block">

            	<h4 class="card-title">SUPPLIER LIST</h4>
            	<h6 class="card-subtitle">Description About Panel</h6> 


				<div class="list-group">
					<?php foreach ($customer as $key => $value) { ?>

						<a href="#" class="list-group-item companyoff" 
						data-companyname = "<?= $value['company_name']; ?>"
						data-registrationno = "<?= $value['company_registeration_no']; ?>"
						data-address = "<?= $value['address']; ?>"
						data-zipcode = "<?= $value['zip_code']; ?>"
						data-country = "<?= $value['country']; ?>"
						data-state = "<?= $value['state']; ?>"
						data-city = "<?= $value['city']; ?>"
						data-telephoneno = "<?= $value['telephone_no']; ?>"
						data-faxno = "<?= $value['fax_no']; ?>"
						data-email = "<?= $value['email']; ?>"
						data-website = "<?= $value['website']; ?>"
						data-typeoftax = "<?= $value['type_of_tax']; ?>"
						data-tax = "<?= $value['tax']; ?>"
						data-term = "<?= $value['term']; ?>"


						>
						<?php echo $value['company_name'] ?></a>

					<?php } ?>

				</div>



        	</div>
       	</div>
    </div>



</div>
