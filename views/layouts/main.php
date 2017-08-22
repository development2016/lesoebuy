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
    <link rel="shortcut icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl; ?>/image/favicon.ico"/>
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

var idleState = false;

    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        clearTimeout(idleTimer);
        if (idleState == true) { 

            var online = 1;
            $.ajax({
                type: 'POST',
                url: 'site/online',
                data: {online: online},
    

            })


           
        }
        idleState = false;
        idleTimer = setTimeout(function () { 

            var idle = 3;
            $.ajax({
                type: 'POST',
                url: 'site/idle',
                data: {idle: idle},
    

            })


            idleState = true; 
        }, 300000);
    });
    $("body").trigger("mousemove");

}); 

JS;
$this->registerJs($script);
?>



<?php $this->beginBody() ?>
<body class="fix-sidebar fix-header card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->

                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo Yii::$app->request->baseUrl; ?>">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/icon.png" alt="homepage" class="dark-logo" />
                         <img src="<?php echo Yii::$app->request->baseUrl; ?>/materialpro/assets/images/text2.png" class="light-logo" alt="homepage" />
                         <!-- Light Logo text -->    
                        </span> </a>
                </div>



                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
        
                        
                    </ul>

                    <ul class="navbar-nav my-lg-0">

                        <?php if ($notify == 0) { ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > <i class="mdi mdi-bell"></i>
                                <div class="notify">  </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                <ul>
                                    <li>
                                        <div class="drop-title">You Have <?php echo $notify ?> Notifications</div>
                                    </li>

                                    <li>
                                        <?= Html::a('<strong>Check all notifications</strong> <i class="fa fa-angle-right"></i>', ['notification/index','id'=>Yii::$app->user->identity->id],['class'=>'nav-link text-center','title'=>'Check all notifications']) ?>
                                    </li>
                                </ul>
                            </div>
                        </li>




                        <?php } else { ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell-ring"></i>
                                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                    <ul>
                                        
                                        <li>
                                            <div class="drop-title">You Have <?php echo $notify ?> Notifications</div>
                                        </li>
                                        
                                        <li>
                                            
                                            <div class="message-center">
                                            <?php foreach ($listnotify as $key => $value) { ?>

                                            <?php $imgProfile = User::find()->where(['account_name'=>$value['from_who']])->one(); 

                                                if (empty($imgProfile->img)) {
                                                    
                                                    $img = 'leso/leso.png';
                                                } else {
                                                    $img = $imgProfile->img;
                                                }


                                            ?>




                                            <?php if (empty($value['remark'])) { ?>

                                                <?= Html::a(
                                                '
                                                <div class="user-img"> <img src="'.Yii::$app->request->baseUrl.'/image/'.$img.'" alt="user" class="img-circle"></div>
                                                <div class="mail-contnet">
                                                <h5 style="color:#009efb;">'.$value['details'].'</h5>
                                                <h6>Project No : '.$value['project_no'].'</h6>
                                                <span class="mail-desc">From : '.$imgProfile->name.'</span>

                                                </div>
                                                ', 
                                                [
                                                    'notification/get',
                                                    'id'=> (string)$value['_id'],

                                                ]) ?>

                                            <?php } else { ?>

                                                <?= Html::a(
                                                '
                                                <div class="user-img"> <img src="'.Yii::$app->request->baseUrl.'/image/'.$img.'" alt="user" class="img-circle"></div>
                                                <div class="mail-contnet">
                                                <h5 style="color:#009efb;">'.$value['details'].'</h5>
                                                <h6>Project No : '.$value['project_no'].'</h6>
                                                <span class="mail-desc">From : '.$imgProfile->name.'</span>

                                                <span class="time">Remark : <b style="color:#dc3030;">'.$value['remark'].'</b></span>
                                                </div>
                                                ', 
                                                [
                                                    'notification/get',
                                                    'id'=> (string)$value['_id'],

                                                ]) ?>



                                            <?php } ?>







                                            <?php } ?>
                                            </div>
                                            
                                        </li>
                                        
                                        <li>
                                            <?= Html::a('<strong>Check all notifications</strong> <i class="fa fa-angle-right"></i>', ['notification/index','id'=>Yii::$app->user->identity->id],['class'=>'nav-link text-center','title'=>'Check all notifications']) ?>

                                        </li>

                                    </ul>
                                </div>
                            </li>





                        <?php } ?>



                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->name; ?></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                    <?php
                    echo Menu::widget([
                        'items' => [
                            [
                                'label' => 'Profile', 
                                'url' => ['user/view','id'=>Yii::$app->user->identity->id],
  
                            ],
                            [
                                'label' => 'Change Password', 
                                'url' => ['/site/request-password-reset','id'=>Yii::$app->user->identity->id],
  
                            ],



                            [
                                'label' => 'List User', 
                                'url' => ['user/list-user','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3009'),
                                //'options'=>['id'=>'user-pulsate'],
                             
                            ],

                            /*[
                                'label' => 'Manage Branch', 
                                'url' => ['/company/manage-warehouse','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3010'),
                                ,
                          
                            ],*/
                            [
                                'label' => ' Manage Company', 
                                'url' => ['/company/manage-company','company_id'=>(string)$company->company],
                                'visible' => User::checkMenu('3011'),
                                //'options'=>['id'=>'company-pulsate'],
   
                            ],

                            [
                                'label' => 'Log out', 
                                'url' => ['site/logout'],
                                'template'=> '<a href="{url}" class="" data-method="POST"><i class="fa fa-power-off"></i> {label}</a>',

                            ],



                        ],
                        'options' => [
                            'class' => 'dropdown-user',
         
                            //'id'=>'pulsate-regular'


                        ],
                       //'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],

                        'encodeLabels' => false,

                    ]);
                    ?>



                            </div>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->

                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">

                    <?php
                    echo Menu::widget([
                        'items' => [
                            [
                                'label' => 'PROCUREMENT',
                                'options'=>['class'=>'nav-small-cap'],
                            ],
                            [
                                'label' => 'Dashboard', 
                                'url' => ['site/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-home"></i><span class="hide-menu" data-toggle="tooltip" title="Dashboard">{label}</a>',
                        
                                //'options'=>['class'=>'active open selected'],
                            ],
                            [
                                'label' => 'Create', 
                                'url' => ['source/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-file"></i><span class="hide-menu" data-toggle="tooltip" title="Create">{label}</a>',
                                'visible' => User::checkMenu('3001'),
                            ],
                            [
                                'label' => 'Requisition', 
                                'url' => ['request/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-folder"></i><span class="hide-menu" data-toggle="tooltip" title="Requisition">{label}</a>',
                                'visible' => User::checkMenu('3002'),
                            ],
                            [
                                'label' => 'Order', 
                                'url' => ['order/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-arrange-bring-forward"></i><span class="hide-menu" data-toggle="tooltip" title="Order">{label}</a>',
                                'visible' => User::checkMenu('3003'),
                            ],
                            [
                                'label' => 'Approval', 
                                'url' => ['request/request'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-file-check"></i><span class="hide-menu" data-toggle="tooltip" title="Approval">{label}</a>',
                                'visible' => User::checkMenu('3008'),
                            ],
                            [
                                'label' => '',
                                'options'=>['class'=>'nav-devider'],
                            ],
                            [
                                'label' => 'OTHERS',
                                'options'=>['class'=>'nav-small-cap'],
                            ],
                            
                            [
                                'label' => 'Supplier', 
                                'url' => ['company-offline/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-factory"></i><span class="hide-menu" data-toggle="tooltip" title="Supplier">{label}</a>',
                                'visible' => User::checkMenu('3013'),
                            ],
                            [
                                'label' => 'Item', 
                                'url' => ['item-offline/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu" data-toggle="tooltip" title="Item">{label}</a>',
                                'visible' => User::checkMenu('3014'),
                            ],
                            /*[
                                'label' => 'Tutorial', 
                                'url' => ['site/tutorial'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-video"></i><span class="hide-menu" data-toggle="tooltip" title="Tutorial">{label}</a>',
                                //'options'=>['class'=>'active open selected'],
                            ],*/
                            [
                                'label' => 'List Project', 
                                'url' => ['project/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-pen"></i><span class="hide-menu" data-toggle="tooltip" title="Project">{label}</a>',
                                //'options'=>['class'=>'active open selected'],
                            ],
                            [
                                'label' => 'Delivery Address', 
                                'url' => ['delivery-address/index'],
                                'template'=> '<a href="{url}" class="text-uppercase" aria-expanded="false"><i class="mdi mdi-arrange-bring-to-front"></i><span class="hide-menu" data-toggle="tooltip" title="Delivery Address">{label}</a>',
                                //'options'=>['class'=>'active open selected'],
                            ],
                            /*[
                                'label' => 'History', 
                                'url' => ['#'],
                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                'visible' => User::checkMenu('3012'),
                            ],*/


                        ],
                        'options' => [
                            'class' => 'sidebarnav',

                        ],
                        //'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],
                        'activateParents'=>true,
                        'encodeLabels' => false,

                    ]);
                    ?>


                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <?= Html::a('<i class="ti-settings"></i>', ['setting/index'], ['class' => 'link','data-toggle'=>'tooltip','title'=>'Settings']) ?>
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Inbox"><i class="mdi mdi-email"></i></a>
                <!-- item-->

                <?= Html::a('<i class="mdi mdi-power"></i>', ['site/logout'],['class'=>'link','data-toggle'=>'tooltip','title'=>'Logout','data-method'=>'POST']) ?>


            </div>
            <!-- End Bottom points-->
        </aside>

        <div class="page-wrapper">

            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0"><?= Html::encode($this->title) ?></h3>

                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>


                    </div>
                    <!-- <div class="col-md-7 col-4 align-self-center">
                        <div class="d-flex m-t-10 justify-content-end">
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                                    <h4 class="m-t-0 text-info">$58,356</h4></div>
                                <div class="spark-chart">
                                    <div id="monthchart"></div>
                                </div>
                            </div>
                            <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                                <div class="chart-text m-r-10">
                                    <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                                    <h4 class="m-t-0 text-primary">$48,356</h4></div>
                                <div class="spark-chart">
                                    <div id="lastmonthchart"></div>
                                </div>
                            </div>

                        </div>
                    </div> -->
                </div>



                <?= $content ?>


            </div>

            <footer class="footer">
                © 2017 Lesoebuy
            </footer>

        </div>

    </div>


</body>
<?php $this->endBody() ?>

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
