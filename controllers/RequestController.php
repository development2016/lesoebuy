<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupTitle;
use app\models\Chat;
use app\models\User;
use app\models\UserCompany;
use app\models\Item;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use kartik\mpdf\Pdf;
use app\models\AsiaebuyCompany;
use app\models\LookupTerm;
use app\models\GeneratePurchaseOrderNo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Log;
use app\models\Notification;
use app\models\Email;

class RequestController extends Controller
{

    public function actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');


        $model = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ], 
            [
                '$match' => [
                    '$or' => [
                            [
                                'sellers.status' => 'Request Approval'
                            ],
                            [
                                'sellers.status' => 'Approve'
                            ],
                            [
                                'sellers.status' => 'Approve Next'
                            ],

                            [
                                'sellers.status' => 'Pending Approval'
                            ],
                            [
                                'sellers.status' => 'Pass PR to Buyer To Proceed PO'
                            ],
                            [
                                'sellers.status' => 'Process'
                            ],

                            [
                                'sellers.temp_status' => 'Change Buyer'
                            ],
                            [
                                'sellers.status' => 'PO In Progress'
                            ],
                            [
                                'sellers.status' => 'Request Approval Next'
                            ],
                            [
                                'sellers.status' => 'Reject PR'
                            ],
                            [
                                'sellers.status' => 'Reject PR Next'
                            ],

                            [
                                'sellers.status' => 'PO Revise'
                            ],

                    ],
                    '$and' => [
                            [
                                'buyers.buyer' => $user->account_name
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
                    'requester' => ['$first'=> '$requester'],
                    'project_no' => ['$first' => '$project_no' ],
                    'request_role' => ['$first' => '$request_role' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            'approver' => '$sellers.approver',
                            'temp_status' => '$sellers.temp_status',
                            'approve_by' => '$sellers.approve_by',
                            'approver_level' => '$sellers.approver_level',
                            'PO_process_by' => '$sellers.PO_process_by',
                            'has_second_approval' => '$sellers.has_second_approval'
                            
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



        $collectionLog = Yii::$app->mongo->getCollection('log');
        $log = $collectionLog->aggregate([

            [
                '$match' => [
                    '$and' => [
                        [
                            'by' => $user->account_name
                        ],

                            
                    ],
                    '$or' => [
                        [
                            'status' => 'Reject PR'
                        ],
                        [
                            'status' => 'Revise PO'
                        ],
                        [
                            'status' => 'Request Approval Next'
                        ],
                        [
                            'status' => 'Change Buyer'
                        ],



                    ],

                    
                ]
            ],
            [

                '$group' => [
                    '_id' => '$project_no',
                    'info' => [
                        '$push' => [
                            'status' => '$status',
                            'date_reject' => '$date_reject',
                            'date_request' => '$date_request',
                            'date_change' => '$date_change',
                            'seller' => '$seller',
                            'purchase_requisition_no' => '$purchase_requisition_no',
                            'purchase_order_no' => '$purchase_order_no',
                            'log_id' => '$_id',
                            'by' => '$by',
                            'project' => '$0'


                        ]
                    ],




                    //'status' => ['$first' => '$status' ],
                    //'purchase_requisition_no' => ['$first' => '$purchase_requisition_no' ],

                    /*'date_reject' => [
                        '$push' => '$date_reject' 
                    ],
                    'log_id' => [
                        '$push' => '$_id' 
                    ],*/
                    /*'0' => [
                        '$push' => '$0'
                    ]*/



                    /*'0' => [
                        //'$push' => '$0',
                        [
                            'date_reject' => '$0.date_reject',

                        ]
                    ],*/
                    /*'0' => [
                        '$push' => [
                            'title' => '$0',

                            
                        ],
                        
                    ],*/
                    
                ]
            ],


        ]);


       //print_r($log);
       //exit();



        return $this->render('index',[
            'model' => $model,
            'user'=> $user,
            'log'=> $log


        ]);

    }


    public function actionRequest()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();

    	$collection = Yii::$app->mongo->getCollection('project');
		$model = $collection->aggregate([
			[
				'$unwind' => '$sellers'
			], 

			[
				'$match' => [
					'$or' => [

    				        [
                                'sellers.status' => 'Request Approval'
                            ],
                            [
                                'sellers.status' => 'Pending Approval'
                            ],
                            [
                                'sellers.status' => 'Reject PR'
                            ],
                            [
                                'sellers.status' => 'Request Approval Next'
                            ],



        
					],
                    '$and' => [
                            [
                                'sellers.approval.approval' => $user->account_name
                            ],
        

                    ],



                    'sellers.status' => [
                        '$ne' => 'PR Cancel'
                    ]
    
                    

  

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
                    'request_role' => ['$first' => '$request_role' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'approver' => '$sellers.approver',
                            'items' => '$sellers.items',
                            'approver_level' => '$sellers.approver_level',



                            
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

        $collectionLog = Yii::$app->mongo->getCollection('log');
        $log = $collectionLog->aggregate([

            [
                '$match' => [

                    '$or' => [
                        [
                            'status' => 'Approve'
                        ],
                        [
                            'status' => 'Reject PR'
                        ],


                    ],
                    '$and' => [

                        [
                            'by_approval' => $user->account_name
                        ],

                    ],

                    
                ]
            ],
            [

                '$group' => [
                    '_id' => '$project_no',
                    'info' => [
                        '$push' => [
                            'status' => '$status',
                            'date_reject' => '$date_reject',
                            'date_approve' => '$date_approve',
                            'seller' => '$seller',
                            'purchase_requisition_no' => '$purchase_requisition_no',
                            'purchase_order_no' => '$purchase_order_no',
                            'log_id' => '$_id',
                            'by_approval' => '$by_approval',
                            'by' => '$by',
                            'project' => '$0'


                        ]
                    ],




                    
                ]
            ],
            [
                '$sort' => [
                    '_id' => -1
                ]
            ],

        ]);




        return $this->render('request',[
        	'model' => $model,
            'user' => $user,
            'log' => $log,

        ]);

    }






    public function actionDirectPurchaseRequisition($project,$buyer,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver
        ]);

    }


    public function actionDirectPurchaseRequisitionResubmit($project,$buyer,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $notification = Notification::find()->where(['project_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-resubmit',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
            'approver' => $approver,
            'notification' => $notification
        ]);

    }


    public function actionDirectPurchaseRequisitionResubmitNext($project,$buyer,$seller,$approver)
    {


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $notification = Notification::find()->where(['project_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-resubmit-next',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver,
             'notification' => $notification
        ]);

    }




    public function actionDirectPurchaseRequisitionCheck($project,$buyer,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-check',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver
        ]);




    }








    public function actionDirectPurchaseRequisitionApprove($project,$buyer,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $notification = Notification::find()->where(['project_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-approve',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
            'approver' => $approver,
            'notification'=> $notification
        ]);


    }


    public function actionDirectPurchaseRequisitionApproveNext($project,$buyer,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $notification = Notification::find()->where(['project_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-approve-next',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver,
             'notification' => $notification
        ]);


    }





    public function actionDirectPurchaseOrder($project,$buyer,$seller)
    {

        $getUser = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'warehouses' => '$sellers.warehouses',
                            'purchase_order_no_revise' => '$sellers.purchase_order_no_revise',
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $buyer;

        }

        if (empty($purchase_order_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseOrderNo = GeneratePurchaseOrderNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;

            if (empty($checkPurchaseOrderNo->purchase_order_no)) {

                $runninNo = 1000;
                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();

            } else {


                $po = substr($checkPurchaseOrderNo->purchase_order_no, 2);
                $new = $po + 1;
                $runninNo = $new;

                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_order_no' => $purchase_order_no,
                'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),
                'sellers.$.status' => 'PO In Progress',
                'sellers.$.PO_process_by' => $buyer_info->account_name,

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

      

        } else {



        }

        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'warehouses' => '$sellers.warehouses',
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'PO_process_by' => '$sellers.PO_process_by',
                            'purchase_order_no_revise' => '$sellers.purchase_order_no_revise',
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'getUser' => $getUser,
            'buyer'=> $buyer
        ]);

    }




    public function actionApprove($project,$seller,$approver)
    {


        $approval_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        if ($approver == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $checkApprover = $collection->aggregate([
                    [
                        '$unwind' => '$sellers'
                    ], 
                    [
                        '$unwind' => '$sellers.approval'
                    ], 
                    [
                        '$match' => [
                            '$and' => [
                                    [
                                        'sellers.approval.status' => ''
                                    ],
                            ],


                            '_id' => $newProject_id,
                            'sellers.seller' => $seller,
                        ]
                    ],


                    [
                        '$group' => [
                            '_id' => '$_id',
                            'title' => ['$first' => '$title' ],
                            'sellers' => [
                                '$push' => [
                                    'approval' => '$sellers.approval',

                                    
                                ],
                                
                            ],


                    
                        ]
                    ],



                ]);



                if (empty($checkApprover[0]['sellers'])) {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndex = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);



                    foreach ($checkIndex[0]['sellers'][0]['approval'] as $key => $value) {

                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
              
                
                        }
                    }


                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.status' => 'Approve',
                                'sellers.$.approver_level' => ''

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);

                        $dataApprove = $collection->aggregate([
                            [
                                '$unwind' => '$sellers'
                            ],
                            [
                                '$match' => [
                                    '$and' => [
                                        [
                                            '_id' => $newProject_id
                                        ],
                                        [
                                            'sellers.seller' => $seller,
                                        ],
                                    ],
                                    
                                ]
                            ],
              

                        ]); 

                        $dataApproveLog = serialize($dataApprove);


                        $collectionLog = Yii::$app->mongo->getCollection('log');
                        $collectionLog->insert([
                            'status' => 'Approve',
                            'date_approve' => date('Y-m-d h:i:s'),
                            'by_approval' => $approval_info->account_name,
                            'project_no' => $dataApprove[0]['project_no'],
                            'seller' => $dataApprove[0]['sellers']['seller'],
                            'purchase_requisition_no' => $dataApprove[0]['sellers']['purchase_requisition_no'],
                            unserialize($dataApproveLog)

                        ]);

                        $last = Log::find()->orderBy(['_id' => SORT_DESC])->one();

                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.last_id_approve_in_log' => $last->_id,


                            ],

                                        
                        ];

                        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                        

                        $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

                        $notify->status_buyer = 'Complete';
                        $notify->status_approver = 'Approve';
                        $notify->details = $dataApprove[0]['sellers']['purchase_requisition_no'];
                        $notify->date_request = date('Y-m-d H:i:s');
                        $notify->project_no = $dataApprove[0]['project_no'];
                        $notify->project_id = $newProject_id;
                        $notify->from_who = $approval_info->account_name;
                        $notify->to_who = $dataApprove[0]['buyers'][0]['buyer'];
                        $notify->date_create = date('Y-m-d H:i:s');
                        $notify->read_unread = 0;
                        $notify->url = 'request/index';
                        $notify->seller = $dataApprove[0]['sellers']['seller'];
                        $notify->approver = $dataApprove[0]['sellers']['approver'];;


                        $notify->save();
/* 
                        //email  start
                        $to_email = User::find()->where(['account_name'=>$dataApprove[0]['buyers'][0]['buyer']])->one();
                       
                        $to = $to_email->email;
                        $subject = 'PURCHASE REQUISITION APPROVE';

                        $url = '<a href="https://lesoebuy.com/request/index">https://lesoebuy.com/request/index</a>';

                        $text = '
                        You Have <b>1</b> Approve Purchase Requisition From <b>'.$approval_info->account_name.'</b><br>
                        Project No : '.$dataApprove[0]['project_no'].'
                        <br>
                        PR No : <b>'.$dataApprove[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

                        $email = new Email();
                        $email->from = 'noreply@lesoebuy.com';
                        $email->to = $to;
                        $email->from_who = $approval_info->account_name;
                        $email->to_who = $dataApprove[0]['buyers'][0]['buyer'];
                        $email->subject = $subject;
                        $email->text = $text;
                        $email->url = $url;
                        $email->date_create = date('Y-m-d H:i:s');
                        $email->project_no = $dataApprove[0]['project_no'];
                        $email->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('noreply@lesoebuy.com')
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setHtmlBody($text)
                            ->send();

 */



                        // update status to approve
                        // update status approver to approve

                    
                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndexs = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],



                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);







                    foreach ($checkIndexs[0]['sellers'][0]['approval'] as $key => $value) {


                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
                            $newKey = $getKey+1;
                
                        }

                        
                    }



                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.approval.'.$newKey.'.status' => 'Waiting Approval',

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);

                        // check next approver
                    $checkApproverNext = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$unwind' => '$sellers.approval'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],


                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);

                    $checkApproverNext[0]['sellers'][0]['approval']['approval'];

                    // save status to next approver to approve
                    $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approver_level' => $checkApproverNext[0]['sellers'][0]['approval']['approval'],


                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);


                        $dataApprove = $collection->aggregate([
                            [
                                '$unwind' => '$sellers'
                            ],
                            [
                                '$match' => [
                                    '$and' => [
                                        [
                                            '_id' => $newProject_id
                                        ],
                                        [
                                            'sellers.seller' => $seller,
                                        ],
                                    ],
                                    
                                ]
                            ],
              

                        ]); 

                        $dataApproveLog = serialize($dataApprove);


                        $collectionLog = Yii::$app->mongo->getCollection('log');
                        $collectionLog->insert([
                            'status' => 'Approve',
                            'date_approve' => date('Y-m-d h:i:s'),
                            'by_approval' => $approval_info->account_name,
                            'project_no' => $dataApprove[0]['project_no'],
                            'seller' => $dataApprove[0]['sellers']['seller'],
                            'purchase_requisition_no' => $dataApprove[0]['sellers']['purchase_requisition_no'],
                            unserialize($dataApproveLog)

                        ]);


                        $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

                        $notify->status_buyer = 'Active';
                        $notify->status_approver = 'Next Approver';
                        $notify->details = $dataApprove[0]['sellers']['purchase_requisition_no'];
                        $notify->date_request = date('Y-m-d H:i:s');
                        $notify->project_no = $dataApprove[0]['project_no'];
                        $notify->project_id = $newProject_id;
                        $notify->from_who = $dataApprove[0]['buyers'][0]['buyer']; 
                        $notify->to_who = $dataApprove[0]['sellers']['approver_level'];
                        $notify->date_create = date('Y-m-d H:i:s');
                        $notify->read_unread = 0;
                        $notify->url = 'request/direct-purchase-requisition-approve';
                        $notify->seller = $dataApprove[0]['sellers']['seller'];
                        $notify->approver = $dataApprove[0]['sellers']['approver'];;


                        $notify->save();
/* 

                        //email  start
                        $to_email = User::find()->where(['account_name'=>$dataApprove[0]['sellers']['approver_level']])->one();

                        $to = $to_email->email;
                        $subject = 'REQUEST APPROVAL';

                        $url = '<a href="https://lesoebuy.com/request/direct-purchase-requisition-approve?project='.$newProject_id.'&buyer='.$dataApprove[0]['buyers'][0]['buyer'].'&seller='.$dataApprove[0]['sellers']['seller'].'&approver='.$dataApprove[0]['sellers']['approver'].'">https://lesoebuy.com/request/direct-purchase-requisition-approve?project='.$newProject_id.'&buyer='.$dataApprove[0]['buyers'][0]['buyer'].'&seller='.$dataApprove[0]['sellers']['seller'].'&approver='.$dataApprove[0]['sellers']['approver'].'</a>';

                        $text = '
                        You Have <b>1</b> Purchase Requisition From <b>'.$dataApprove[0]['buyers'][0]['buyer'].'</b> To Approve <br>
                        Project No : '.$dataApprove[0]['project_no'].'
                        <br>
                        PR No : <b>'.$dataApprove[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

                        $email = new Email();
                        $email->from = 'noreply@lesoebuy.com';
                        $email->to = $to;
                        $email->from_who = $dataApprove[0]['buyers'][0]['buyer'];
                        $email->to_who = $dataApprove[0]['sellers']['approver_level'];
                        $email->subject = $subject;
                        $email->text = $text;
                        $email->url = $url;
                        $email->date_create = date('Y-m-d H:i:s');
                        $email->project_no = $dataApprove[0]['project_no'];
                        $email->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('noreply@lesoebuy.com')
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setHtmlBody($text)
                            ->send();
*/





                }



        } else {

            $collection = Yii::$app->mongo->getCollection('project');
            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '_id' => $newProject_id,
                        'sellers.seller' => $seller,
                    ]
                ],

            ]); 

            $pr_history = serialize($model[0]['sellers']);
            $purchase_requisition_no = $model[0]['sellers']['purchase_requisition_no'];

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Approve',
                    'sellers.$.approve_by' => $approval_info->account_name,

                ],
                '$addToSet' => [
                    'history' => [
                        'pr_history' => [unserialize($pr_history)],

                    ],

                ],
                            
            ];

            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


            $dataApprove = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '$and' => [
                            [
                                '_id' => $newProject_id
                            ],
                            [
                                'sellers.seller' => $seller,
                            ],
                        ],
                        
                    ]
                ],
  

            ]); 

            $dataApproveLog = serialize($dataApprove);


            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Approve',
                'date_approve' => date('Y-m-d h:i:s'),
                'by_approval' => $approval_info->account_name,
                'project_no' => $dataApprove[0]['project_no'],
                'seller' => $dataApprove[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataApprove[0]['sellers']['purchase_requisition_no'],
                unserialize($dataApproveLog)

            ]);

            $last = Log::find()->orderBy(['_id' => SORT_DESC])->one();

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'sellers.$.last_id_approve_in_log' => $last->_id,


                ],

                            
            ];

            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            
            $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();
            $notify->status_buyer = 'Complete';
            $notify->status_approver = 'Approve';
            $notify->details = $dataApprove[0]['sellers']['purchase_requisition_no'];
            $notify->date_request = date('Y-m-d H:i:s');
            $notify->project_no = $dataApprove[0]['project_no'];
            $notify->project_id = $newProject_id;
            $notify->from_who = $dataApprove[0]['sellers']['approval'][0]['approval'];
            $notify->to_who = $dataApprove[0]['buyers'][0]['buyer'];
            $notify->date_create = date('Y-m-d H:i:s');
            $notify->read_unread = 0;
            $notify->url = 'request/index';
            $notify->url_for_buyer = 'request/direct-purchase-requisition';
            $notify->seller = $dataApprove[0]['sellers']['seller'];
            $notify->approver = $dataApprove[0]['sellers']['approver'];


            $notify->save();
/* 
            //email  start
            $to_email = User::find()->where(['account_name'=>$dataApprove[0]['buyers'][0]['buyer']])->one();
           
            $to = $to_email->email;
            $subject = 'PURCHASE REQUISITION APPROVE';

            $url = '<a href="https://lesoebuy.com/request/index">https://lesoebuy.com/request/index</a>';

            $text = '
            You Have <b>1</b> Approve Purchase Requisition From <b>'.$dataApprove[0]['sellers']['approval'][0]['approval'].'</b><br>
            Project No : '.$dataApprove[0]['project_no'].'
            <br>
            PR No : <b>'.$dataApprove[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

            $email = new Email();
            $email->from = 'noreply@lesoebuy.com';
            $email->to = $to;
            $email->from_who = $dataApprove[0]['sellers']['approval'][0]['approval'];
            $email->to_who = $dataApprove[0]['buyers'][0]['buyer'];
            $email->subject = $subject;
            $email->text = $text;
            $email->url = $url;
            $email->date_create = date('Y-m-d H:i:s');
            $email->project_no = $dataApprove[0]['project_no'];
            $email->save();

            Yii::$app->mailer->compose()
                ->setFrom('noreply@lesoebuy.com')
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($text)
                ->send();
*/



        }


        return $this->redirect(['request/request']);
    }


    public function actionApproveNext($project,$seller,$approver)
    {


        $approval_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        if ($approver == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $checkApprover = $collection->aggregate([
                    [
                        '$unwind' => '$sellers'
                    ], 
                    [
                        '$unwind' => '$sellers.approval'
                    ], 
                    [
                        '$match' => [
                            '$and' => [
                                    [
                                        'sellers.approval.status' => ''
                                    ],
                            ],


                            '_id' => $newProject_id,
                            'sellers.seller' => $seller,
                        ]
                    ],


                    [
                        '$group' => [
                            '_id' => '$_id',
                            'title' => ['$first' => '$title' ],
                            'sellers' => [
                                '$push' => [
                                    'approval' => '$sellers.approval',

                                    
                                ],
                                
                            ],


                    
                        ]
                    ],



                ]);



                if (empty($checkApprover[0]['sellers'])) {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndex = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);



                    foreach ($checkIndex[0]['sellers'][0]['approval'] as $key => $value) {

                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
              
                
                        }
                    }


                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.status' => 'Approve Next',
                                'sellers.$.approver_level' => ''

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);



                        $dataApproveNext = $collection->aggregate([
                            [
                                '$unwind' => '$sellers'
                            ],
                            [
                                '$match' => [
                                    '$and' => [
                                        [
                                            '_id' => $newProject_id
                                        ],
                                        [
                                            'sellers.seller' => $seller,
                                        ],
                                    ],
                                    
                                ]
                            ],
              

                        ]); 

                        $dataApproveNextLog = serialize($dataApproveNext);


                        $collectionLog = Yii::$app->mongo->getCollection('log');
                        $collectionLog->insert([
                            'status' => 'Approve',
                            'date_approve' => date('Y-m-d h:i:s'),
                            'by_approval' => $approval_info->account_name,
                            'project_no' => $dataApproveNext[0]['project_no'],
                            'seller' => $dataApproveNext[0]['sellers']['seller'],
                            'purchase_requisition_no' => $dataApproveNext[0]['sellers']['purchase_requisition_no'],
                            unserialize($dataApproveNextLog)

                        ]);

                        $last = Log::find()->orderBy(['_id' => SORT_DESC])->one();

                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.last_id_approve_in_log' => $last->_id,


                            ],

                                        
                        ];

                        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



                        $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

                        $notify->status_buyer = 'Complete';
                        $notify->status_approver = 'Approve';
                        $notify->details = $dataApproveNext[0]['sellers']['purchase_requisition_no'];
                        $notify->date_request = date('Y-m-d H:i:s');
                        $notify->project_no = $dataApproveNext[0]['project_no'];
                        $notify->project_id = $newProject_id;
                        $notify->from_who = $dataApproveNext[0]['sellers']['approver_level'];
                        $notify->to_who = $dataApproveNext[0]['buyers'][0]['buyer'];
                        $notify->date_create = date('Y-m-d H:i:s');
                        $notify->read_unread = 0;
                        $notify->url = 'request/index';
                        $notify->seller = $dataApproveNext[0]['sellers']['seller'];
                        $notify->approver = $dataApproveNext[0]['sellers']['approver'];;


                        $notify->save();
/* 
                        //email  start
                        $to_email = User::find()->where(['account_name'=>$dataApproveNext[0]['buyers'][0]['buyer']])->one();
                       
                        $to = $to_email->email;
                        $subject = 'PURCHASE REQUISITION APPROVE';

                        $url = '<a href="https://lesoebuy.com/request/index">https://lesoebuy.com/request/index</a>';
              

                        $text = '
                        You Have <b>1</b> Approve Purchase Requisition From <b>'.$approval_info->account_name.'</b><br>
                        Project No : '.$dataApproveNext[0]['project_no'].'
                        <br>
                        PR No : <b>'.$dataApproveNext[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

                        $email = new Email();
                        $email->from = 'noreply@lesoebuy.com';
                        $email->to = $to;
                        $email->from_who = $approval_info->account_name;
                        $email->to_who = $dataApproveNext[0]['buyers'][0]['buyer'];
                        $email->subject = $subject;
                        $email->text = $text;
                        $email->url = $url;
                        $email->date_create = date('Y-m-d H:i:s');
                        $email->project_no = $dataApproveNext[0]['project_no'];
                        $email->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('noreply@lesoebuy.com')
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setHtmlBody($text)
                            ->send();

*/


                        // update status to approve
                        // update status approver to approve

                    
                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndexs = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],



                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);



                    foreach ($checkIndexs[0]['sellers'][0]['approval'] as $key => $value) {


                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
                            $newKey = $getKey+1;
                
                        }

                        
                    }



                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.approval.'.$newKey.'.status' => 'Waiting Approval',

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);

                        // check next approver
                    $checkApproverNext = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$unwind' => '$sellers.approval'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],


                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);

                    $checkApproverNext[0]['sellers'][0]['approval']['approval'];

                    // save status to next approver to approve
                    $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approver_level' => $checkApproverNext[0]['sellers'][0]['approval']['approval'],


                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);


                        $dataApproveNext = $collection->aggregate([
                            [
                                '$unwind' => '$sellers'
                            ],
                            [
                                '$match' => [
                                    '$and' => [
                                        [
                                            '_id' => $newProject_id
                                        ],
                                        [
                                            'sellers.seller' => $seller,
                                        ],
                                    ],
                                    
                                ]
                            ],
              

                        ]); 

                        $dataApproveNextLog = serialize($dataApproveNext);


                        $collectionLog = Yii::$app->mongo->getCollection('log');
                        $collectionLog->insert([
                            'status' => 'Approve',
                            'date_approve' => date('Y-m-d h:i:s'),
                            'by_approval' => $approval_info->account_name,
                            'project_no' => $dataApproveNext[0]['project_no'],
                            'seller' => $dataApproveNext[0]['sellers']['seller'],
                            'purchase_requisition_no' => $dataApproveNext[0]['sellers']['purchase_requisition_no'],
                            unserialize($dataApproveNextLog)

                        ]);


                        $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

                        $notify->status_buyer = 'Active';
                        $notify->status_approver = 'Next Approver';
                        $notify->details = $dataApproveNext[0]['sellers']['purchase_requisition_no'];
                        $notify->date_request = date('Y-m-d H:i:s');
                        $notify->project_no = $dataApproveNext[0]['project_no'];
                        $notify->project_id = $newProject_id;
                        $notify->from_who = $dataApproveNext[0]['buyers'][0]['buyer']; 
                        $notify->to_who = $dataApproveNext[0]['sellers']['approver_level'];
                        $notify->date_create = date('Y-m-d H:i:s');
                        $notify->read_unread = 0;
                        $notify->url = 'request/direct-purchase-requisition-approve-next';
                        $notify->seller = $dataApproveNext[0]['sellers']['seller'];
                        $notify->approver = $dataApproveNext[0]['sellers']['approver'];;


                        $notify->save();
/* 

                        //email  start
                        $to_email = User::find()->where(['account_name'=>$dataApproveNext[0]['sellers']['approver_level']])->one();

                        $to = $to_email->email;
                        $subject = 'REQUEST APPROVAL';

                        $url = '<a href="https://lesoebuy.com/request/direct-purchase-requisition-approve-next?project='.$newProject_id.'&buyer='.$dataApproveNext[0]['buyers'][0]['buyer'].'&seller='.$dataApproveNext[0]['sellers']['seller'].'&approver='.$dataApproveNext[0]['sellers']['approver'].'" >https://lesoebuy.com/request/direct-purchase-requisition-approve-next?project='.$newProject_id.'&buyer='.$dataApproveNext[0]['buyers'][0]['buyer'].'&seller='.$dataApproveNext[0]['sellers']['seller'].'&approver='.$dataApproveNext[0]['sellers']['approver'].'</a>';

                        $text = '
                        You Have <b>1</b> Purchase Requisition From <b>'.$dataApproveNext[0]['buyers'][0]['buyer'].'</b> To Approve <br>
                        Project No : '.$dataApproveNext[0]['project_no'].'
                        <br>
                        PR No : <b>'.$dataApproveNext[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

                        $email = new Email();
                        $email->from = 'noreply@lesoebuy.com';
                        $email->to = $to;
                        $email->from_who = $dataApproveNext[0]['buyers'][0]['buyer'];
                        $email->to_who = $dataApproveNext[0]['sellers']['approver_level'];
                        $email->subject = $subject;
                        $email->text = $text;
                        $email->url = $url;
                        $email->date_create = date('Y-m-d H:i:s');
                        $email->project_no = $dataApproveNext[0]['project_no'];
                        $email->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('noreply@lesoebuy.com')
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setHtmlBody($text)
                            ->send();

*/





                }



        } else {

            $collection = Yii::$app->mongo->getCollection('project');
            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '_id' => $newProject_id,
                        'sellers.seller' => $seller,
                    ]
                ],

            ]); 


            $purchase_requisition_no = $model[0]['sellers']['purchase_requisition_no'];
            $pr_history = serialize($model[0]['sellers']);

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Approve Next',
                    'sellers.$.approve_by' => $approval_info->account_name,

                ],
                '$addToSet' => [
                    'history' => [
                        'pr_history_next' => [unserialize($pr_history)],

                    ],

                ],
                            
            ];

            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            $dataApproveNext = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '$and' => [
                            [
                                '_id' => $newProject_id
                            ],
                            [
                                'sellers.seller' => $seller,
                            ],
                        ],
                        
                    ]
                ],
  

            ]); 

            $dataApproveNextLog = serialize($dataApproveNext);


            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Approve',
                'date_approve' => date('Y-m-d h:i:s'),
                'by_approval' => $approval_info->account_name,
                'project_no' => $dataApproveNext[0]['project_no'],
                'seller' => $dataApproveNext[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataApproveNext[0]['sellers']['purchase_requisition_no'],
                unserialize($dataApproveNextLog)

            ]);

            $last = Log::find()->orderBy(['_id' => SORT_DESC])->one();

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'sellers.$.last_id_approve_in_log' => $last->_id,


                ],

                            
            ];



            $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();
            $notify->status_buyer = 'Complete';
            $notify->status_approver = 'Approve';
            $notify->details = $dataApproveNext[0]['sellers']['purchase_requisition_no'];
            $notify->date_request = date('Y-m-d H:i:s');
            $notify->project_no = $dataApproveNext[0]['project_no'];
            $notify->project_id = $newProject_id;
            $notify->from_who = $dataApproveNext[0]['sellers']['approval'][0]['approval'];
            $notify->to_who = $dataApproveNext[0]['buyers'][0]['buyer'];
            $notify->date_create = date('Y-m-d H:i:s');
            $notify->read_unread = 0;
            $notify->url = 'request/index';
            $notify->seller = $dataApproveNext[0]['sellers']['seller'];
            $notify->approver = $dataApproveNext[0]['sellers']['approver'];;


            $notify->save();
/* 
            //email  start
            $to_email = User::find()->where(['account_name'=>$dataApproveNext[0]['buyers'][0]['buyer']])->one();
           
            $to = $to_email->email;
            $subject = 'PURCHASE REQUISITION APPROVE';

            $url = '<a href="https://lesoebuy.com/request/index">https://lesoebuy.com/request/index</a>';

            $text = '
            You Have <b>1</b> Approve Purchase Requisition From <b>'.$dataApproveNext[0]['sellers']['approval'][0]['approval'].'</b><br>
            Project No : '.$dataApproveNext[0]['project_no'].'
            <br>
            PR No : <b>'.$dataApproveNext[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

            $email = new Email();
            $email->from = 'noreply@lesoebuy.com';
            $email->to = $to;
            $email->from_who = $dataApproveNext[0]['sellers']['approval'][0]['approval'];
            $email->to_who = $dataApproveNext[0]['buyers'][0]['buyer'];
            $email->subject = $subject;
            $email->text = $text;
            $email->url = $url;
            $email->date_create = date('Y-m-d H:i:s');
            $email->project_no = $dataApproveNext[0]['project_no'];
            $email->save();

            Yii::$app->mailer->compose()
                ->setFrom('noreply@lesoebuy.com')
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($text)
                ->send();

*/



        }


        return $this->redirect(['request/request']);
    }






    public function actionChooseBuyer($project,$seller,$buyer,$role)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;

        if ($role == 'user') {

            $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                RIGHT JOIN acl_menu ON acl_menu.id = acl.acl_menu_id
                WHERE acl.company_id ='".$returnCompanyBuyer->company."' AND acl_menu.role_id = 3100

                GROUP BY user.username");
            $buyer_list = $sql->queryAll();


        } elseif ($role == 'buyer') {

            $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                RIGHT JOIN acl_menu ON acl_menu.id = acl.acl_menu_id
                WHERE acl.company_id ='".$returnCompanyBuyer->company."' AND acl_menu.role_id = 3100 AND acl.user_id != '".Yii::$app->user->identity->id."'

                GROUP BY user.username");
            $buyer_list = $sql->queryAll();




        }



        if ($model->load(Yii::$app->request->post()) ) {

            if ($role == 'user') {

                foreach ($_POST['Project']['sellers']['buyer'] as $key => $value) {
                    
                    $tempApp[] = [
                        'buyer' => $value,
            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'buyers' =>  $tempApp,
                        'sellers.$.status' => 'Pass PR to Buyer To Proceed PO'

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif ($role == 'buyer') {

                foreach ($_POST['Project']['sellers']['buyer'] as $key => $value) {
                    
                    $tempApp[] = [
                        'buyer' => $value,
            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'buyers' =>  $tempApp,
                        'from_buyer' => $buyer,
                        'sellers.$.status' => 'Pass PR to Buyer To Proceed PO'
                        

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



            }

            $collection = Yii::$app->mongo->getCollection('project');
            $dataChangeBuyer = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '$and' => [
                            [
                                '_id' => $newProject_id
                            ],
                            [
                                'sellers.seller' => $seller,
                            ],
                        ],
                        
                    ]
                ],
  

            ]); 

            //print_r($dataChangeBuyer[0]['buyers']);


            $dataChangeBuyerLog = serialize($dataChangeBuyer);


            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Change Buyer',
                'date_change' => date('Y-m-d h:i:s'),
                'from_who' => $buyer,
                'to_who' => $tempApp,
                'by' => $buyer,
                'purchase_requisition_no' => $dataChangeBuyer[0]['sellers']['purchase_requisition_no'],
                'seller' => $dataChangeBuyer[0]['sellers']['seller'],
                'project_no' => $dataChangeBuyer[0]['project_no'],
                unserialize($dataChangeBuyerLog)

            ]);


                $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

                $notify->status_buyer = 'Change Buyer';
                $notify->details = $dataChangeBuyer[0]['sellers']['purchase_requisition_no'];
                $notify->date_request = date('Y-m-d H:i:s');
                $notify->project_no = $dataChangeBuyer[0]['project_no'];
                $notify->project_id = $newProject_id;
                $notify->from_who = $buyer;
                $notify->to_who = $dataChangeBuyer[0]['buyers'][0]['buyer'];
                $notify->date_create = date('Y-m-d H:i:s');
                $notify->read_unread = 0;
                $notify->url = 'request/index';
                $notify->seller = $dataChangeBuyer[0]['sellers']['seller'];
                $notify->approver = $dataChangeBuyer[0]['sellers']['approver'];;


                $notify->save();
/* 

                //email  start
                $to_email = User::find()->where(['account_name'=>$dataChangeBuyer[0]['buyers'][0]['buyer']])->one();

                $to = $to_email->email;
                $subject = 'CHANGE BUYER';

                $url = '<a href="https://lesoebuy.com/request/index">https://lesoebuy.com/request/index</a>';

                $text = '
                You Have <b>1</b> Purchase Requisition From <b>'.$buyer.'</b> To Proceed <br>
                Project No : '.$dataChangeBuyer[0]['project_no'].'
                <br>
                PR No : <b>'.$dataChangeBuyer[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

                $email = new Email();
                $email->from = 'noreply@lesoebuy.com';
                $email->to = $to;
                $email->from_who = $buyer;
                $email->to_who = $dataChangeBuyer[0]['buyers'][0]['buyer'];
                $email->subject = $subject;
                $email->text = $text;
                $email->url = $url;
                $email->date_create = date('Y-m-d H:i:s');
                $email->project_no = $dataChangeBuyer[0]['project_no'];
                $email->save();

                Yii::$app->mailer->compose()
                    ->setFrom('noreply@lesoebuy.com')
                    ->setTo($to)
                    ->setSubject($subject)
                    ->setHtmlBody($text)
                    ->send();
*/

            

             return $this->redirect(['request/index']);

        } else {

           return $this->renderAjax('choose-buyer',[
                'buyer_list' => $buyer_list,
                'model' => $model,

            ]);

        }



    }



    public function actionChooseApproval($project,$seller,$buyer,$type)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();

        $data_approver = $model['sellers'][0]['approver'];
        $data_approval = serialize($model['sellers'][0]['approval']);


        if ($model->load(Yii::$app->request->post()) ) {

                foreach ($_POST['Project']['sellers']['approval'] as $key => $value) {
                    
                    $tempApp[] = [
                        'approval' => $value,

            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'sellers.$.approver' => 'normal',
                        'sellers.$.approval' =>  $tempApp,
                        'sellers.$.status' => 'Process',
                        'buyers'=> [[
                            'buyer' => $buyer
                        ]],

                    ],
                    '$addToSet' => [
                        'progress_approver' => [
                            'approver' => $data_approver,
                            'approval' => unserialize($data_approval)
                        ],

                    ],

                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            return $this->redirect(['request/direct-purchase-requisition-check','project'=>$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>'normal']);

            

        } else {

           return $this->renderAjax('choose-approval',[
                'approval' => $approval,
                'model' => $model,

            ]);

        }



    }


    public function actionChooseApprovalLevel($project,$seller,$buyer,$type)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();


       return $this->renderAjax('choose-approval-level',[
            'approval' => $approval,
            'model' => $model,
            'buyer' => $buyer,
            'project' => $project,
            'seller' => $seller,
            'type' => $type

        ]);

        


    }




    public function actionLevel()
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($_POST['project']);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$_POST['buyer']])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();


        $form = ActiveForm::begin(['action' =>['request/approver'], 'id' => 'forum_post', 'method' => 'post',]);

        echo "<input type='hidden' name='project' value='".$_POST['project']."' />";
        echo "<input type='hidden' name='seller' value='".$_POST['seller']."' />";
        echo "<input type='hidden' name='type' value='".$_POST['type']."' />";
        echo "<input type='hidden' name='buyer' value='".$_POST['buyer']."' />";

        $a=0;
        for ($i=0; $i < $_POST['level']; $i++) { $a++;

            echo "<label>Approver Level ".$a."</label>";
            echo "<select class='form-control' name='approval[approver_no_".$a."]'>";
            foreach ($approval as $key => $value) {
                echo "<option>".$value['account_name']."</option>";
            }
        
            echo "</select>";
            echo "<br>";

        }
        echo "<br>";


        echo "<div class='form-group'>";
        echo Html::submitButton($model->isNewRecord ? 'Choose' : 'Choose', ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']);
        echo "</div>";

        ActiveForm::end();



    }

    public function actionApprover()
    {

        $buyer_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($_POST['project']);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $data_approver = $model['sellers'][0]['approver'];
        $data_approval = serialize($model['sellers'][0]['approval']);



        foreach ($_POST['approval'] as $key => $value) {
            
            $tempApp[] = [
                'approval' => $value,
                'status' => ''
    
            ];

           

        }



        $collection = Yii::$app->mongo->getCollection('project');

        $arrUpdate = [
            '$set' => [
                'sellers.$.approver' => 'level',
                'sellers.$.approval' =>  $tempApp,
                'sellers.$.status' => 'Process',
                'buyers'=> [[
                    'buyer' => $buyer_info->account_name
                ]],

            ],
            '$addToSet' => [
                'progress_approver' => [
                    'approver' => $data_approver,
                    'approval' => unserialize($data_approval)
                ],

            ],



        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdate);


        $model = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '_id' => $_POST['project'],
                    'sellers.seller' => $_POST['seller'],
                ]
            ],

        ]); 


        $arrUpdateNext = [
            '$set' => [
                'sellers.$.approval.0.status' => 'Waiting Approval',
                'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],

            ]
        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdateNext);


        return $this->redirect(['request/direct-purchase-requisition-check','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$buyer_info->account_name,'approver'=>'level']);





    }


    public function actionRejectPr($seller,$project,$approver,$buyer)
    {
        
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],
                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 

        //print_r($data[0]['sellers']);

        $data_reject = serialize($data[0]['sellers']);

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();



        if ($model->load(Yii::$app->request->post()) ) {


            if ($approver == 'level') {


                foreach ($data[0]['sellers'][0]['approval'] as $key => $value) {
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            
                                '$set' => [
                                    'sellers.$.approval.'.$key.'.status' => '',
                                    'sellers.$.status' => 'Reject PR',

                                ],
                                '$addToSet' => [
                                    'pr_reject' => [
                                        'pr_no' => $data[0]['sellers'][0]['purchase_requisition_no'],
                                        'reject_by' => $user->account_name,
                                        'remark' => $_POST['Project']['sellers']['pr_reject']['remark'],
                                        'data_reject' => unserialize($data_reject)
                                    ],

                                ],

                            
                        ]

                    );



                }





            } else {

                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            
                                '$set' => [
                                    'sellers.$.status' => 'Reject PR',


                                ],
                                '$addToSet' => [
                                    'pr_reject' => [
                                        'pr_no' => $data[0]['sellers'][0]['purchase_requisition_no'],
                                        'reject_by' => $user->account_name,
                                        'remark' => $_POST['Project']['sellers']['pr_reject']['remark'],
                                        'data_reject' => unserialize($data_reject)
                                    ],

                                ],
                            
                        ]

                    );



            }





            $dataRejectPr = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '$and' => [
                            [
                                '_id' => $newProject_id
                            ],
                            [
                                'sellers.seller' => $seller,
                            ],
                        ],
                        
                    ]
                ],
  

            ]); 

            $dataRejectPrLog = serialize($dataRejectPr);



            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Reject PR',
                'date_reject' => date('Y-m-d h:i:s'),
                'by_approval' => $user->account_name,
                'by' => $buyer,
                'project_no' => $dataRejectPr[0]['project_no'],
                'seller' => $dataRejectPr[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataRejectPr[0]['sellers']['purchase_requisition_no'],
                unserialize($dataRejectPrLog)

            ]);


            $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

            $notify->status_buyer = 'Reject';
            $notify->status_approver = 'Reject PR';
            $notify->details = $dataRejectPr[0]['sellers']['purchase_requisition_no'];
            $notify->date_request = date('Y-m-d H:i:s');
            $notify->project_no = $dataRejectPr[0]['project_no'];
            $notify->project_id = $newProject_id;
            $notify->from_who = $dataRejectPr[0]['sellers']['approval'][0]['approval'];
            $notify->to_who = $dataRejectPr[0]['buyers'][0]['buyer'];
            $notify->date_create = date('Y-m-d H:i:s');
            $notify->read_unread = 0;
            $notify->url = 'request/direct-purchase-requisition-resubmit';
            $notify->seller = $dataRejectPr[0]['sellers']['seller'];
            $notify->approver = $dataRejectPr[0]['sellers']['approver'];
            $notify->remark = $_POST['Project']['sellers']['pr_reject']['remark'];

            $notify->save();
/* 
            //email  start
            $to_email = User::find()->where(['account_name'=>$buyer])->one();
           
            $to = $to_email->email;
            $subject = 'PURCHASE REQUISITION REJECT';


            $url = '<a href="https://lesoebuy.com/request/direct-purchase-requisition-resubmit?project='.$newProject_id.'&buyer='.$buyer.'&seller='.$dataRejectPr[0]['sellers']['seller'].'&approver='.$dataRejectPr[0]['sellers']['approver'].'">https://lesoebuy.com/request/direct-purchase-requisition-resubmit?project='.$newProject_id.'&buyer='.$buyer.'&seller='.$dataRejectPr[0]['sellers']['seller'].'&approver='.$dataRejectPr[0]['sellers']['approver'].'</a>';

            $text = '
            You Have <b>1</b> Rejected Purchase Requisition From <b>'.$dataRejectPr[0]['sellers']['approval'][0]['approval'].'</b><br>
            Project No : '.$dataRejectPr[0]['project_no'].'
            <br>
            PR No : <b>'.$dataRejectPr[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

            $email = new Email();
            $email->from = 'noreply@lesoebuy.com';
            $email->to = $to;
            $email->from_who = $dataRejectPr[0]['sellers']['approval'][0]['approval'];
            $email->to_who = $buyer;
            $email->subject = $subject;
            $email->text = $text;
            $email->url = $url;
            $email->date_create = date('Y-m-d H:i:s');
            $email->project_no = $dataRejectPr[0]['project_no'];
            $email->save();

            Yii::$app->mailer->compose()
                ->setFrom('noreply@lesoebuy.com')
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($text)
                ->send();

*/




            return $this->redirect(['request/request']);


        } else {

               return $this->renderAjax('reject',[
                    'model' => $model,
                    'buyer' => $buyer,
                    'project' => $project,
                    'seller' => $seller,
                    'approver' => $approver

                ]);

        }


    }


    public function actionRejectPrNext($seller,$project,$approver,$buyer)
    {
        
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],
                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 

        //print_r($data[0]['sellers']);

        $data_reject = serialize($data[0]['sellers']);

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();



        if ($model->load(Yii::$app->request->post()) ) {


            if ($approver == 'level') {


                foreach ($data[0]['sellers'][0]['approval'] as $key => $value) {
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            
                                '$set' => [
                                    'sellers.$.approval.'.$key.'.status' => '',
                                    'sellers.$.status' => 'Reject PR Next',

                                ],
                                '$addToSet' => [
                                    'pr_reject' => [
                                        'pr_no' => $data[0]['sellers'][0]['purchase_requisition_no'],
                                        'reject_by' => $user->account_name,
                                        'remark' => $_POST['Project']['sellers']['pr_reject']['remark'],
                                        'data_reject' => unserialize($data_reject)
                                    ],

                                ],

                            
                        ]

                    );



                }





            } else {

                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            
                                '$set' => [
                                    'sellers.$.status' => 'Reject PR Next',


                                ],
                                '$addToSet' => [
                                    'pr_reject' => [
                                        'pr_no' => $data[0]['sellers'][0]['purchase_requisition_no'],
                                        'reject_by' => $user->account_name,
                                        'remark' => $_POST['Project']['sellers']['pr_reject']['remark'],
                                        'data_reject' => unserialize($data_reject)
                                    ],

                                ],
                            
                        ]

                    );



            }





            $dataRejectPr = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '$and' => [
                            [
                                '_id' => $newProject_id
                            ],
                            [
                                'sellers.seller' => $seller,
                            ],
                        ],
                        
                    ]
                ],
  

            ]); 

            $dataRejectPrLog = serialize($dataRejectPr);



            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Reject PR',
                'date_reject' => date('Y-m-d h:i:s'),
                'by_approval' => $user->account_name,
                'by' => $buyer,
                'project_no' => $dataRejectPr[0]['project_no'],
                'seller' => $dataRejectPr[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataRejectPr[0]['sellers']['purchase_requisition_no'],
                unserialize($dataRejectPrLog)

            ]);


            $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

            $notify->status_buyer = 'Reject';
            $notify->status_approver = 'Reject PR';
            $notify->details = $dataRejectPr[0]['sellers']['purchase_requisition_no'];
            $notify->date_request = date('Y-m-d H:i:s');
            $notify->project_no = $dataRejectPr[0]['project_no'];
            $notify->project_id = $newProject_id;
            $notify->from_who = $dataRejectPr[0]['sellers']['approval'][0]['approval'];
            $notify->to_who = $dataRejectPr[0]['buyers'][0]['buyer'];
            $notify->date_create = date('Y-m-d H:i:s');
            $notify->read_unread = 0;
            $notify->url = 'request/direct-purchase-requisition-resubmit-next';
            $notify->seller = $dataRejectPr[0]['sellers']['seller'];
            $notify->approver = $dataRejectPr[0]['sellers']['approver'];
            $notify->remark = $_POST['Project']['sellers']['pr_reject']['remark'];


            $notify->save();
    /* 

            //email  start
            $to_email = User::find()->where(['account_name'=>$dataRejectPr[0]['buyers'][0]['buyer']])->one();
           
            $to = $to_email->email;
            $subject = 'PURCHASE REQUISITION REJECT';

            $url = '<a href="https://lesoebuy.com/request/direct-purchase-requisition-resubmit-next?project='.$newProject_id.'&buyer='.$dataRejectPr[0]['buyers'][0]['buyer'].'&seller='.$dataRejectPr[0]['sellers']['seller'].'&approver='.$dataRejectPr[0]['sellers']['approver'].'">https://lesoebuy.com/request/direct-purchase-requisition-resubmit-next?project='.$newProject_id.'&buyer='.$dataRejectPr[0]['buyers'][0]['buyer'].'&seller='.$dataRejectPr[0]['sellers']['seller'].'&approver='.$dataRejectPr[0]['sellers']['approver'].'</a>';


            $text = '
            You Have <b>1</b> Rejected Purchase Requisition From <b>'.$dataRejectPr[0]['sellers']['approval'][0]['approval'].'</b><br>
            Project No : '.$dataRejectPr[0]['project_no'].'
            <br>
            PR No : <b>'.$dataRejectPr[0]['sellers']['purchase_requisition_no'].'</b> <p></p> link : '.$url.'';

            $email = new Email();
            $email->from = 'noreply@lesoebuy.com';
            $email->to = $to;
            $email->from_who = $dataRejectPr[0]['sellers']['approval'][0]['approval'];
            $email->to_who = $dataRejectPr[0]['buyers'][0]['buyer'];
            $email->subject = $subject;
            $email->text = $text;
            $email->url = $url;
            $email->date_create = date('Y-m-d H:i:s');
            $email->project_no = $dataRejectPr[0]['project_no'];
            $email->save();

            Yii::$app->mailer->compose()
                ->setFrom('noreply@lesoebuy.com')
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($text)
                ->send();

*/

            return $this->redirect(['request/request']);


        } else {

               return $this->renderAjax('reject',[
                    'model' => $model,
                    'buyer' => $buyer,
                    'project' => $project,
                    'seller' => $seller,
                    'approver' => $approver

                ]);

        }


    }






    public function actionDirectPurchaseOrderRevise($project,$buyer,$seller)
    {

        $getUser = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'count_revise' => '$sellers.count_revise',
                            'revise_status' => '$sellers.revise_status',
                            'purchase_order_no_revise' => '$sellers.purchase_order_no_revise',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $buyer;
            $count_revise = $value['sellers'][0]['count_revise'];
            $revise_status = $value['sellers'][0]['revise_status'];

        }

        if ($revise_status == 'Progress') {

            $sum = $count_revise;

            $new_purchase_order_no =  '-R'.$sum;

            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.purchase_order_no_revise' => $new_purchase_order_no,
                    'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),
                    'sellers.$.revise_status' => 'On Going',
                    'sellers.$.PO_process_by' => $buyer_info->account_name,

                ]
            
            ];
            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



        } else if ($revise_status == 'On Going') {



        }







        $list = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],

                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'warehouses' => '$sellers.warehouses',
                            'approver' => '$sellers.approver',
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'PO_process_by' => '$sellers.PO_process_by',
                            'purchase_order_no_revise' => '$sellers.purchase_order_no_revise',
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-order-revise',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'getUser' => $getUser,
            'buyer'=> $buyer
        ]);

    }


    public function actionReviseGenerate($seller,$project,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'PO In Progress',
                    'sellers.$.revise_status' => '',


                ]
            
            ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        $dataRevisePo = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$and' => [
                        [
                            '_id' => $newProject_id
                        ],
                        [
                            'sellers.seller' => $seller,
                        ],
                    ],
                    
                ]
            ],


        ]); 

        $dataRevisePoLog = serialize($dataRevisePo);


        $collectionLog = Yii::$app->mongo->getCollection('log');
        $collectionLog->insert([
            'status' => 'PO Regenerate',
            'date' => date('Y-m-d h:i:s'),
            'by' => $buyer,
            'project_no' => $dataRevisePo[0]['project_no'],
            'seller' => $dataRevisePo[0]['sellers']['seller'],
            'purchase_requisition_no' => $dataRevisePo[0]['sellers']['purchase_requisition_no'],

            unserialize($dataRevisePoLog)

        ]);




        return $this->redirect([
            'request/direct-purchase-order',
            'project' => $project,
            'buyer' => $buyer,
            'seller' => $seller,
            ]);


    }






}