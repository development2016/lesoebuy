<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAssetLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'materialpro/assets/plugins/bootstrap/css/bootstrap.min.css',
        'materialpro/css/style.css',
        'materialpro/css/colors/red.css',
        'css/site.css'

    ];
    public $js = [
    'materialpro/assets/plugins/bootstrap/js/tether.min.js',
    'materialpro/assets/plugins/bootstrap/js/bootstrap.min.js',
    'materialpro/js/jquery.slimscroll.js',
    'materialpro/js/waves.js',
    'materialpro/js/sidebarmenu.js',
    'materialpro/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js',
    'materialpro/assets/plugins/sparkline/jquery.sparkline.min.js',
    'materialpro/js/custom.min.js',
    'materialpro/assets/plugins/styleswitcher/jQuery.style.switcher.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
