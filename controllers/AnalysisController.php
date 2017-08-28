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
        $ids = ['220','207','218','205','203','206','214','215','197','210','198'];

        $buyer = User::find()->where(['in', 'id', $ids])->all();
   

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

        $item =  empty($_POST['item']) ? "" : $_POST['item'];

		$collection = Yii::$app->mongo->getCollection('project');


        if ($buyer == "" && $supplier == "" && $item == "") {

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


        } elseif ($buyer != "" && $supplier != "" && $item != "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ],
                                [
                                    'sellers.seller' => $supplier
                                ],

                        
                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
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

        } elseif ($buyer != "" && $supplier == "" && $item == "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
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




        } elseif ($buyer != "" && $supplier != "" && $item == "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ],
                                [
                                    'sellers.seller' => $supplier
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




        } elseif ($buyer != "" && $supplier == "" && $item != "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ],

                        
                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
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




        } elseif ($buyer == "" && $supplier != "" && $item == "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'sellers.seller' => $supplier
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





        } elseif ($buyer == "" && $supplier == "" && $item != "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],

                        
                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
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




        } elseif ($buyer == "" && $supplier != "" && $item != "") {

            $totalPOstatus= $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'sellers.seller' => $supplier
                                ],

                        
                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
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






        echo "<table class='table table-hover'>";
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
        		echo "<td>".Html::a($value_all_status['count'], [
                    'view',
                    'buyer'=>$value_all_status['_id'][0],
                    'status'=>$status,
                    'item' => $item,
                    'supplier' => $supplier
                    ], ['class' => 'btn btn-outline-info'])."</td>";
                echo "<td>";
                    $end_total = $total_all = 0;
                        foreach ($value_all_status['itemsSold'] as $key_n => $value_n) {


                            //print_r($value_n['total_po']);
                            $total_po_temp =  empty($value_n['total_po']) ? "" : $value_n['total_po'];


                            $total_all += $total_po_temp;
                            
                        
                        }

                        //exit();
                        echo "<h3><b>".$end_total = $total_all."</b></h3>";

                echo "</td>";
        	echo "</tr>";



        }
        echo "</tbody>";
        echo "</table>";



    }

    public function actionView($buyer,$status,$item,$supplier)
    {

        $suppliers =  empty($supplier) ? "" : $supplier;

        $collection = Yii::$app->mongo->getCollection('project'); 

        if ($supplier == "") {

            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ], 
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ],

                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
                                ],




                        ],




                        
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$_id',
                        'title' => ['$first' => '$title' ],
                        'due_date' => ['$first' => '$due_date'],
                        'date_create' => ['$first' => '$date_create'],
                        'description' => ['$first' => '$description' ],
                        'url_myspot' => ['$first' => '$url_myspot' ],
                        'type_of_project' => ['$first' => '$type_of_project' ],
                        'quotation_file' => ['$first' => '$quotation_file' ],
                        'buyers' => ['$first' => '$buyers' ],
                        'project_no' => ['$first' => '$project_no' ],
                        'sellers' => [
                            '$push' => [
                                'quotation_no' => '$sellers.quotation_no',
                                'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                                'purchase_order_no' => '$sellers.purchase_order_no',
                                'status' => '$sellers.status',
                                'approval' => '$sellers.approval',
                                'seller' => '$sellers.seller',
                                'revise' => '$sellers.revise',
                                'last_id_approve_in_log' => '$sellers.last_id_approve_in_log',
                                'items' => '$sellers.items',
                                
                            ],
                            
                        ],


                
                    ]
                ],
                [
                    '$sort' => [
                        '_id' => -1
                    ]
                ],


            ]);





        } else {


            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ], 
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => $status
                                ],
                                [
                                    'buyers.buyer' => $buyer
                                ],
                                [
                                    'sellers.seller' => $suppliers
                                ],



                        ],
                        '$or' => [
                                [
                                    'sellers.items.item_code' => [
                                        '$regex' => $item
                                    ]
                                ],
                                [
                                    'sellers.items.item_name' => [
                                        '$regex' => $item
                                    ]
                                ],




                        ],




                        
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$_id',
                        'title' => ['$first' => '$title' ],
                        'due_date' => ['$first' => '$due_date'],
                        'date_create' => ['$first' => '$date_create'],
                        'description' => ['$first' => '$description' ],
                        'url_myspot' => ['$first' => '$url_myspot' ],
                        'type_of_project' => ['$first' => '$type_of_project' ],
                        'quotation_file' => ['$first' => '$quotation_file' ],
                        'buyers' => ['$first' => '$buyers' ],
                        'project_no' => ['$first' => '$project_no' ],
                        'sellers' => [
                            '$push' => [
                                'quotation_no' => '$sellers.quotation_no',
                                'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                                'purchase_order_no' => '$sellers.purchase_order_no',
                                'status' => '$sellers.status',
                                'approval' => '$sellers.approval',
                                'seller' => '$sellers.seller',
                                'revise' => '$sellers.revise',
                                'last_id_approve_in_log' => '$sellers.last_id_approve_in_log',
                                'items' => '$sellers.items',
                                
                            ],
                            
                        ],


                
                    ]
                ],
                [
                    '$sort' => [
                        '_id' => -1
                    ]
                ],


            ]);






        }







        return $this->render('view',[
            'model' => $model,
            'buyer' => $buyer

        ]);

        



    }


     




}