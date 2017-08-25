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
class AppAssetRegister extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'materialpro/assets/plugins/bootstrap/css/bootstrap.min.css',
        'materialpro/css/style.css',
        'materialpro/css/colors/red.css',
        'materialpro/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
        'materialpro/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css',
        

    ];
    public $js = [
        'materialpro/assets/plugins/bootstrap/js/popper.min.js',
        'materialpro/assets/plugins/bootstrap/js/bootstrap.min.js',
        'materialpro/js/jquery.slimscroll.js',
        'materialpro/js/waves.js',
        'materialpro/js/sidebarmenu.js',
        'materialpro/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js',
        'materialpro/assets/plugins/sparkline/jquery.sparkline.min.js',
        'materialpro/js/custom.min.js',
        'materialpro/assets/plugins/styleswitcher/jQuery.style.switcher.js',
        'materialpro/assets/plugins/datatables/jquery.dataTables.min.js',
        'materialpro/assets/plugins/moment/moment.js',
        'materialpro/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
        'materialpro/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
