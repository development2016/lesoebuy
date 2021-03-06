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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'materialpro/assets/plugins/bootstrap/css/bootstrap.min.css',
        'materialpro/css/style.css',
        'materialpro/css/colors/red-dark.css',
        'materialpro/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
        'materialpro/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css',
        'materialpro/assets/plugins/select2/dist/css/select2.min.css',
        'materialpro/assets/plugins/switchery/dist/switchery.min.css',
        'materialpro/assets/plugins/bootstrap-select/bootstrap-select.min.css',
        'materialpro/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css',
        'materialpro/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css',
        'materialpro/assets/plugins/multiselect/css/multi-select.css',
        'css/site.css',
        'materialpro/assets/plugins/chartist-js/dist/chartist.min.css',
        'materialpro/assets/plugins/chartist-js/dist/chartist-init.css',
        'materialpro/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css',
        'materialpro/assets/plugins/c3-master/c3.min.css',
    

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
        'materialpro/assets/plugins/datatables/jquery.dataTables.min.js',
        'materialpro/assets/plugins/moment/moment.js',
        'materialpro/assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
        'materialpro/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js',
        'materialpro/assets/plugins/chartist-js/dist/chartist.min.js',
        'materialpro/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js',
        'materialpro/assets/plugins/d3/d3.min.js',
        'materialpro/assets/plugins/c3-master/c3.min.js',
        'js/dashboard1.js',
        'materialpro/assets/plugins/styleswitcher/jQuery.style.switcher.js',
        'materialpro/assets/plugins/switchery/dist/switchery.min.js',
        'materialpro/assets/plugins/select2/dist/js/select2.full.min.js',
        'materialpro/assets/plugins/bootstrap-select/bootstrap-select.min.js',
        'materialpro/assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
        'materialpro/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
        'materialpro/assets/plugins/multiselect/js/jquery.multi-select.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
