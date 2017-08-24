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
use app\models\UploadForm;
use yii\web\UploadedFile;


class AnalysisController extends Controller
{
	public function actionIndex()
    {
        return $this->render('index');

    }



    public function actionReport()
    {

		$status =  empty($_POST['status']) ? "" : $_POST['status'];

		$collection = Yii::$app->mongo->getCollection('project');
        $totalPOAll= $collection->aggregate([
            [
                '$match' => [
                    '$and' => [
                            [
                                'sellers.status' => $status
                            ],
                            
                     
                    ],
                ]
            ],
            [
                '$group' => [
                    '_id' => '$buyers.buyer',
                    'count' => [
                            '$sum' => 1
                    ],



            
                ]
            ],

        ]);

        echo "<table class='table'>";
        echo "    <thead>";
        echo "        <tr>";
        echo "            <th>Buyer</th>";
        echo "            <th>Total PO</th>";
        echo "        </tr>";
        echo "   </thead>";
        echo "   <tbody>";
        foreach ($totalPOAll as $key_all => $value_all) {

        	echo "<tr>";
        		echo "<td>".$value_all['_id'][0]."</td>";
        		echo "<td>".$value_all['count']."</td>";
        	echo "</tr>";



        }
        echo "</tbody>";
        echo "</table>";





    }

}