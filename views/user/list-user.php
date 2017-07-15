<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';


$script = <<< JS
$(document).ready(function(){

    $('#create').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('.role').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });





}); 
JS;
$this->registerJs($script);
$buyer = $approval = $user= $seller = $admin= 0;

?>
<div class="row">
    <div class="col-lg-12">
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
      </ol>
    </div>
</div>

<div class="row">

    <div class="col-lg-6">
      <div class="panel panel-default" >
        <div class="panel-heading" style="background-color: #025b80;overflow: hidden;">

          <h4 class="panel-title pull-left" style="padding-top: 7.5px;padding-bottom: 5px;color: #fff;">CHARTS</h4>

        </div>
        <div class="panel-body">

        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="panel panel-default" >
        <div class="panel-heading" style="background-color: #025b80;overflow: hidden;">

        <h4 class="panel-title pull-left" style="padding-top: 7.5px;color: #fff;">LIST USER</h4>

            <div class="btn-group btn-group-sm pull-right">
              <?= Html::a('Add',FALSE, [
          'value'=>Url::to([
            'user/create',
            'company_id'=>(string)$company->_id,
            'type'=>$company->type
          ]),'class' => 'btn btn-success','id'=>'create']) ?>

    
            </div>


        </div>
        <div class="panel-body">

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Account Name</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php $i=0; foreach ($userList as $key => $value) { $i++;?>
              <tr>
                <td><?php echo $i; ?></td>
                <td>
                    <?php echo $value['account_name'] ?>
                </td>
                <td>
                         <?php  
                         $connection = \Yii::$app->db;
                         $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                          RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                          RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                          RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                          WHERE acl.user_id = "'.$value['id_user'].'" GROUP BY lookup_role.role');
                                                     $model = $sql->queryAll(); ?>

                          <ul>
                          <?php foreach ($model as $key2 => $value2) { ?>

                              <?php if ($value2['as_a'] == 300) { ?>
                                <li><?= $value2['role'] ?></li>

                              <?php } elseif ($value2['as_a'] == 200) { ?>
                                <li><?= $value2['role'] ?></li>
                              <?php } ?>
                             
                          <?php } ?>
                          </ul>
                  </td>
                  <td>
                    <?php echo $value['branch'] ?>
                </td>

              </tr>
            <?php } ?>


            </tbody>

        </table>





        </div>
      </div>
    </div>



</div>