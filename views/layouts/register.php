<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetRegister;
use yii\widgets\Menu;
use yii\bootstrap\Modal;
use app\models\LoginForm;
use yii\helpers\Url;

AppAssetRegister::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<?php $this->beginBody() ?>
<body class="fix-sidebar fix-header boxed card-no-border">

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>


	<div class="p-20 ">

        <div class="container">

    	<?= $content ?>

   		</div> <!-- /container -->

   	</div>




</body>
<?php $this->endBody() ?>

</html>

<?php $this->endPage() ?>
