<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetPrint;
use yii\widgets\Menu;
use yii\bootstrap\Modal;
use app\models\LoginForm;
use yii\helpers\Url;

AppAssetPrint::register($this);


?>
<?php $this->beginPage() ?>

<html>
<head>

	<link rel="shortcut icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl; ?>/image/favicon.ico"/>
    <?= Html::csrfMetaTags() ?>  
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<?php $this->beginBody() ?>
<body >
	<page size="A4">

    	<?= $content ?>
  
	</page>

<?php $this->endBody() ?>

</html>

<?php $this->endPage() ?>
