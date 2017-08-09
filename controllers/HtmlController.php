<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\Company;
use app\models\AsiaebuyCompany;
use app\models\UserCompany;
use app\models\User;
use app\models\LookupState;
use app\models\Log;

class HtmlController extends Controller
{

    // SALE LEAD


    // DIRECT PURCHASE 

    public function actionDirectPurchaseRequisitionHtml($project,$buyer,$seller)
    {
        $this->layout = 'html';

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
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'warehouses' => '$sellers.warehouses',
                                                        'att' => '$sellers.att',
                            'att_email' => '$sellers.att_email',
                            'att_tel' => '$sellers.att_tel',
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);


    }

    public function actionDirectPurchaseRequisitionHtmlCancel($project,$buyer,$seller)
    {
        $this->layout = 'html';

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
                            'delivery_before' => '$sellers.delivery_before',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'warehouses' => '$sellers.warehouses',
                                                        'att' => '$sellers.att',
                            'att_email' => '$sellers.att_email',
                            'att_tel' => '$sellers.att_tel',
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-html-cancel',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);


    }




    public function actionDirectPurchaseRequisitionHtmlInactive($log_id,$buyer)
    {
        $this->layout = 'html';
        $newProject_id = new \MongoDB\BSON\ObjectID($log_id);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $collection = Yii::$app->mongo->getCollection('log');
        $model = $collection->aggregate([
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

        $list = $model[0][0];

        //print_r($list[0]['sellers']);
        //exit();

        return $this->render('direct-purchase-requisition-html-inactive',[

            'list' => $list,
            'companyBuyer' => $companyBuyer,

        ]);



    }


    public function actionDirectPurchaseOrderHtmlInactive($log_id,$buyer)
    {
        $this->layout = 'html';
        $newProject_id = new \MongoDB\BSON\ObjectID($log_id);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $collection = Yii::$app->mongo->getCollection('log');
        $model = $collection->aggregate([
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

        $list = $model[0][0];

        //print_r($list[0]['sellers']);
        //exit();

        return $this->render('direct-purchase-order-html-inactive',[

            'list' => $list,
            'companyBuyer' => $companyBuyer,

        ]);



    }




    public function actionDirectPurchaseOrderHtml($project,$buyer,$seller)
    {
        $this->layout = 'html';

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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'type_of_tax' => '$sellers.type_of_tax',
                            'delivery_before' => '$sellers.delivery_before',
                            'warehouses' => '$sellers.warehouses',
                            'purchase_order_no_revise' => '$sellers.purchase_order_no_revise',
                                                        'att' => '$sellers.att',
                            'att_email' => '$sellers.att_email',
                            'att_tel' => '$sellers.att_tel',
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-order-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);


    }








}