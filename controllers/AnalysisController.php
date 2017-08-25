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
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use app\models\CompanyOffline;


class AnalysisController extends Controller
{
	public function actionIndex()
    {

        $buyer = User::find()->all();
        $supplier = CompanyOffline::find()->all();

        return $this->render('index',[
            'buyer' => $buyer,
            'supplier' => $supplier
        ]);

    }



    public function actionReportStatus()
    {

		$status =  empty($_POST['status']) ? "" : $_POST['status'];

        $buyer =  empty($_POST['buyer']) ? "" : $_POST['buyer'];

        $supplier =  empty($_POST['supplier']) ? "" : $_POST['supplier'];

		$collection = Yii::$app->mongo->getCollection('project');

        if ($status == "" && $buyer == "" && $supplier == "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$buyers.buyer',
                        'count' => [
                                '$sum' => 1
                        ],
                        'itemsSold' => [

                            '$push' => [
                                'total_po' => '$total_po'
                            ]
                        ]


                
                    ]
                ],

            ]);



        } else if ($status == "" && $buyer != "") {
          
            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'buyers.buyer' => $buyer
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
                        'itemsSold' => [

                            '$push' => [
                                'total_po' => '$total_po'
                            ]
                        ]


                
                    ]
                ],

            ]); 

           


        } else if ($status != "" && $buyer == "") {
            
        
            $totalPOstatus= $collection->aggregate([
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
                        'itemsSold' => [

                            '$push' => [
                                'total_po' => '$total_po'
                            ]
                        ]


                
                    ]
                ],

            ]); 
        

            
        } else if ($status != "" && $buyer != "") {
            
        
            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ]

                         
                        ],
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$buyers.buyer',
                        'count' => [
                                '$sum' => 1
                        ],
                        'itemsSold' => [

                            '$push' => [
                                'total_po' => '$total_po'
                            ]
                        ]


                
                    ]
                ],

            ]); 


        }


        echo "<table class='table'>";
        echo "    <thead>";
        echo "        <tr>";
        echo "            <th>Buyer</th>";
        echo "            <th>Total PO</th>";
        echo "            <th>Total Amount (RM)</th>";
        echo "        </tr>";
        echo "   </thead>";
        echo "   <tbody>";
        foreach ($totalPOstatus as $key_all_status => $value_all_status ) {

        	echo "<tr>";
        		echo "<td>".$value_all_status['_id'][0]."</td>";
        		echo "<td>".$value_all_status['count']."</td>";
                echo "<td>";
                    $end_total = $total_all = 0;
                        foreach ($value_all_status['itemsSold'] as $key_n => $value_n) {


                            //print_r($value_n['total_po']);
                            $total_po_temp =  empty($value_n['total_po']) ? "" : $value_n['total_po'];


                            $total_all += $total_po_temp;
                            
                        
                        }

                        //exit();
                        echo "<p class='text-primary'>".$end_total = $total_all."</p>";

                echo "</td>";
        	echo "</tr>";



        }
        echo "</tbody>";
        echo "</table>";



    }




     




}