<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Company;
use app\models\Notification;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<?php 

$company = Company::compid();
$notify = Notification::notify();
$listnotify = Notification::listnotify();




$script = <<< JS
$(document).ready(function(){




}); 
JS;
$this->registerJs($script);
?>



<body>
<?php $this->beginBody() ?>

    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #025b80;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" style="color: #fff;">eBuy</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">




          <ul class="nav navbar-nav navbar-right">


        <?php if ($notify == 0) { ?>



              
        <?php } else { ?>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: #fff;"> Notification : <span class="w3-badge w3-red"><?php echo $notify ?></span></a>
              <ul class="dropdown-menu">

                <?php foreach ($listnotify as $key => $value) { ?>

                    <li>
                        <?= Html::a('
                        <b>From : </b>'.$value['from_who'].'<br>'.'<b>PR NO : </b>'.$value['details'], 
                        [
                            'notification/get',
                            'id'=> (string)$value['_id'],

                        ]) ?>

                        

                    </li>
                    <li role="separator" class="divider"></li>
                <?php } ?>


              </ul>

            </li>


        <?php } ?>




            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: #fff;">Hi, <?php echo Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username; ?><span class="caret"></span></a>
                    <?php
                    echo Menu::widget([
                        'items' => [
                            [
                                'label' => 'My Profile', 
                                'url' => ['site/index'],
  
                            ],
                            [
                                'label' => 'List User', 
                                'url' => ['user/list-user','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3009'),
                                //'options'=>['id'=>'user-pulsate'],
                             
                            ],

                            [
                                'label' => 'Manage Branch', 
                                'url' => ['/company/manage-warehouse','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3010'),
                                //'options'=>['id'=>'warehouse-pulsate'],
                          
                            ],
                            [
                                'label' => ' Manage Company', 
                                'url' => ['/company/manage-company','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3011'),
                                //'options'=>['id'=>'company-pulsate'],
   
                            ],

                            [
                                'label' => 'Log out', 
                                'url' => ['site/logout'],
                                'template'=> '<a href="{url}" class="" data-method="POST">{label}</a>',

                            ],



                        ],
                        'options' => [
                            'class' => 'dropdown-menu',
         
                            //'id'=>'pulsate-regular'


                        ],
                       //'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],

                        'encodeLabels' => false,

                    ]);
                    ?>
            </li>
          </ul>




        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">

            <?php
            echo Menu::widget([
                'items' => [
                    [
                        'label' => 'Dashboard', 
                        'url' => ['site/index'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        //'options'=>['class'=>'active open selected'],
                    ],
                    [
                        'label' => 'Create', 
                        'url' => ['source/index'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        'visible' => User::checkMenu('3001'),
                    ],
                    [
                        'label' => 'Requisition', 
                        'url' => ['request/index'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        'visible' => User::checkMenu('3002'),
                    ],
                    [
                        'label' => 'Supplier', 
                        'url' => ['company-offline/index'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        'visible' => User::checkMenu('3002'),
                    ],
                    [
                        'label' => 'Order', 
                        'url' => ['order/index'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        'visible' => User::checkMenu('3003'),
                    ],
                    [
                        'label' => 'Approval', 
                        'url' => ['request/request'],
                        'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                        'visible' => User::checkMenu('3008'),
                    ],


                ],
                'options' => [
                    'class' => 'nav nav-sidebar',

                ],
                //'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],
                'activateParents'=>true,
                'encodeLabels' => false,

            ]);
            ?>



        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
          <?= $content ?>

        </div>

      </div>
    </div>









<?php $this->endBody() ?>
</body>
</html>


<?php
Modal::begin([
    'header' =>'eBuy',
    'id' => 'modal',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => false, 'keyboard' => TRUE],
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],

]);

echo "<div id='modalContent' ></div>";
Modal::end();


Modal::begin([
    'header' =>'eBuy',
    'id' => 'modalmd',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => false, 'keyboard' => TRUE],
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],

]);

echo "<div id='modalContentMd'></div>";
Modal::end();

?>


<?php $this->endPage() ?>
