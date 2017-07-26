<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupTitle;
use app\models\Message;
use app\models\User;
use app\models\UserCompany;
use app\models\Item;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;
use app\models\GeneratePurchaseRequisitionNo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Notification;


class FileController extends Controller
{


    public function actionIndex($project)
    {
    	$newProject_id = new \MongoDB\BSON\ObjectID($project);
        $collection = Yii::$app->mongo->getCollection('project');
        $file = $collection->aggregate([
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],

                           
                    ],

                ]
            ],

        ]);



        $model = $file[0]['sellers'][0]['direct_purchase'];




        return $this->render('index',[
        	'model' => $model,
        ]);
    }


    public function actionView($path,$extension)
    {   
        $file = Yii::$app->request->baseUrl.'/offline/'.$path;

        if ($extension == 'pdf') {
            echo "<embed src='".$file."' style='width:100%;height:100%;' alt='pdf' >";
        } elseif ($extension == 'xlsx') {
            echo "<embed src='".$file."' style='width:100%;height:100%;' alt='xlsx' >";
        } elseif ($extension == 'docx') {
            echo "<embed src='".$file."' style='width:100%;height:100%;' alt='xlsx' >";

        } else {
        
        }
    
    }





}