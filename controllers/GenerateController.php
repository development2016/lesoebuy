<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\LookupTitle;
use app\models\Item;
use app\models\Company;
use kartik\mpdf\Pdf;
use app\models\LookupTerm;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;
use app\models\User;
use app\models\UserCompany;
use app\models\Log;
use app\models\Notification;


class GenerateController extends Controller
{




    // START FOR SALE LEAD GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE




 // START FOR SPOT GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE




 // START FOR DIRECT PURCHASE GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE


    public function actionGenerateDirectPurchaseRequisition($project,$seller,$approver,$buyer)
    {

        $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();


        $collection = Yii::$app->mongo->getCollection('company');
        $company = $collection->aggregate([
            [
                '$match' => [
                    '_id' => (string)$returnCompanyBuyer->company,

                ]
            ],

        ]); 

        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $projectModel = Project::find()->where(['_id'=>$newProject_id])->one();

        


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




        if ($projectModel->load(Yii::$app->request->post()) ) {


                        if (empty($model[0]['sellers']['warehouses'])) {


                            if ($approver == 'level') {

                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.approval.0.status' => 'Waiting Approval',
                                            'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],
                                            'sellers.$.temp_status' => '',
                                            'sellers.$.warehouses' => [[
                                                'person_in_charge' => $buyer_id->username,
                                                'contact' => $company[0]['telephone_no'],
                                                'country' => $company[0]['country'],
                                                'state' => $company[0]['state'],
                                                'location' => $company[0]['city'],
                                                'warehouse_name' => $company[0]['company_name'],
                                                'address' => $company[0]['address'],
                                                'latitude' => 0,
                                                'longitude' => 0,
                                                'email' => $company[0]['email'],
                                            ]],

                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],



                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                            } else {

                                 $connection = \Yii::$app->db;
                                 $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                                  RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                                  RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                                  RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                                  WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
                                $getRole = $sql->queryAll(); 


                                if ($getRole[0]['role'] == 'User') {


                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.approve_by' => '',
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.temp_status' => '',
                                            'sellers.$.warehouses' => [[
                                                'person_in_charge' => $buyer_id->username,
                                                'contact' => $company[0]['telephone_no'],
                                                'country' => $company[0]['country'],
                                                'state' => $company[0]['state'],
                                                'location' => $company[0]['city'],
                                                'warehouse_name' => $company[0]['company_name'],
                                                'address' => $company[0]['address'],
                                                'latitude' => 0,
                                                'longitude' => 0,
                                                'email' => $company[0]['email'],
                                            ]],


                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],


                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                                 
                                } else {


                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.approve_by' => '',
                                            'sellers.$.temp_status' => '',
                                            'sellers.$.warehouses' => [[
                                                'person_in_charge' => $buyer_id->username,
                                                'contact' => $company[0]['telephone_no'],
                                                'country' => $company[0]['country'],
                                                'state' => $company[0]['state'],
                                                'location' => $company[0]['city'],
                                                'warehouse_name' => $company[0]['company_name'],
                                                'address' => $company[0]['address'],
                                                'latitude' => 0,
                                                'longitude' => 0,
                                                'email' => $company[0]['email'],
                                            ]],



                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],

                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                                }

                            }



                        } else {

                            if ($approver == 'level') {

                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.approval.0.status' => 'Waiting Approval',
                                            'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],
                                            'sellers.$.temp_status' => '',


                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],

                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                            } else {

                                 $connection = \Yii::$app->db;
                                 $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                                  RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                                  RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                                  RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                                  WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
                                $getRole = $sql->queryAll(); 


                                if ($getRole[0]['role'] == 'User') {


                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.approve_by' => '',
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.temp_status' => '',


                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],

                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                                 
                                } else {


                                    $collection = Yii::$app->mongo->getCollection('project');
                                    $arrUpdate = [
                                        '$set' => [
                                            'date_update' => date('Y-m-d h:i:s'),
                                            'update_by' => Yii::$app->user->identity->id,
                                            'sellers.$.status' => 'Request Approval',
                                            'sellers.$.approve_by' => '',
                                            'sellers.$.temp_status' => '',


                                        ],
                                        '$addToSet' => [
                                            'remark_before_approval' => [
                                                'by' => $buyer,
                                                'remark' => $_POST['Project']['sellers']['remark'],

                                            ],

                                        ],
                                    
                                    ];
                                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                                }





                            }



                        }


                            $notify = new Notification();
                            $notify->status_buyer = 'Active';
                            $notify->status_approver = 'Waiting Approval';
                            $notify->details = $model[0]['sellers']['purchase_requisition_no'];
                            $notify->date_request = date('Y-m-d H:i:s');
                            $notify->project_no = $model[0]['project_no'];
                            $notify->project_id = $newProject_id;
                            $notify->from_who = $buyer;
                            $notify->to_who = $model[0]['sellers']['approval'][0]['approval'];
                            $notify->date_create = date('Y-m-d H:i:s');
                            $notify->read_unread = 0;
                            $notify->url = 'request/direct-purchase-requisition-approve';
                            $notify->url_for_buyer = 'request/direct-purchase-requisition';
                            $notify->seller = $model[0]['sellers']['seller'];
                            $notify->approver = $model[0]['sellers']['approver'];;

                            $notify->save();

                    

                        return $this->redirect(['request/index']);

        } else {


            return $this->renderAjax('/source/remark',[
                'projectModel' => $projectModel,


            ]);


        }

    }


    public function actionGenerateDirectPurchaseRequisitionNext($project,$seller,$approver,$buyer)
    {

        $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();


        $collection = Yii::$app->mongo->getCollection('company');
        $company = $collection->aggregate([
            [
                '$match' => [
                    '_id' => (string)$returnCompanyBuyer->company,

                ]
            ],

        ]); 



        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $projectModel = Project::find()->where(['_id'=>$newProject_id])->one();

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

        if ($projectModel->load(Yii::$app->request->post()) ) {

        if (empty($model[0]['sellers']['warehouses'])) {


            if ($approver == 'level') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.approval.0.status' => 'Waiting Approval',
                            'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],
                            'sellers.$.temp_status' => '',
                            'sellers.$.warehouses' => [[
                                'person_in_charge' => $buyer_id->username,
                                'contact' => $company[0]['telephone_no'],
                                'country' => $company[0]['country'],
                                'state' => $company[0]['state'],
                                'location' => $company[0]['city'],
                                'warehouse_name' => $company[0]['company_name'],
                                'address' => $company[0]['address'],
                                'latitude' => 0,
                                'longitude' => 0,
                                'email' => $company[0]['email'],
                            ]],
                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],

                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            } else {

                 $connection = \Yii::$app->db;
                 $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                  RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                  RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                  RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                  WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
                $getRole = $sql->queryAll(); 


                if ($getRole[0]['role'] == 'User') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.approve_by' => '',
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.temp_status' => '',
                            'sellers.$.warehouses' => [[
                                'person_in_charge' => $buyer_id->username,
                                'contact' => $company[0]['telephone_no'],
                                'country' => $company[0]['country'],
                                'state' => $company[0]['state'],
                                'location' => $company[0]['city'],
                                'warehouse_name' => $company[0]['company_name'],
                                'address' => $company[0]['address'],
                                'latitude' => 0,
                                'longitude' => 0,
                                'email' => $company[0]['email'],
                            ]],


                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],



                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                 
                } else {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.approve_by' => '',
                            'sellers.$.temp_status' => '',
                            'sellers.$.warehouses' => [[
                                'person_in_charge' => $buyer_id->username,
                                'contact' => $company[0]['telephone_no'],
                                'country' => $company[0]['country'],
                                'state' => $company[0]['state'],
                                'location' => $company[0]['city'],
                                'warehouse_name' => $company[0]['company_name'],
                                'address' => $company[0]['address'],
                                'latitude' => 0,
                                'longitude' => 0,
                                'email' => $company[0]['email'],
                            ]],



                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],



                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                }

            }



        } else {

            if ($approver == 'level') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.approval.0.status' => 'Waiting Approval',
                            'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],
                            'sellers.$.temp_status' => '',


                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],


                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            } else {

                 $connection = \Yii::$app->db;
                 $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                  RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                  RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                  RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                  WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
                $getRole = $sql->queryAll(); 


                if ($getRole[0]['role'] == 'User') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.approve_by' => '',
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.temp_status' => '',


                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],



                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                 
                } else {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'date_update' => date('Y-m-d h:i:s'),
                            'update_by' => Yii::$app->user->identity->id,
                            'sellers.$.status' => 'Request Approval Next',
                            'sellers.$.approve_by' => '',
                            'sellers.$.temp_status' => '',


                        ],
                        '$addToSet' => [
                            'remark_before_approval_next' => [
                                'by' => $buyer,
                                'remark' => $_POST['Project']['sellers']['remark'],

                            ],

                        ],

                        
                    
                    ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                }





            }



        }


            $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();
            $notify->status_buyer = 'Active';
            $notify->status_approver = 'Waiting Approval';
            $notify->details = $model[0]['sellers']['purchase_requisition_no'];
            $notify->date_request = date('Y-m-d H:i:s');
            $notify->project_no = $model[0]['project_no'];
            $notify->project_id = $newProject_id;
            $notify->from_who = $buyer;
            $notify->to_who = $model[0]['sellers']['approval'][0]['approval'];
            $notify->date_create = date('Y-m-d H:i:s');
            $notify->read_unread = 0;
            $notify->url = 'request/direct-purchase-requisition-approve-next';
            $notify->url_for_buyer = 'request/direct-purchase-requisition';
            $notify->seller = $model[0]['sellers']['seller'];
            $notify->approver = $model[0]['sellers']['approver'];;

            $notify->save();


            return $this->redirect(['request/index']);

        } else {


            return $this->renderAjax('/request/remark',[
                'projectModel' => $projectModel,


            ]);


        }





        

    }







    public function actionGenerateDirectPurchaseOrder($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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

        $po_history = serialize($model[0]['sellers']);


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'PO Completed',
                'sellers.$.temp_status' => '',
                'buyers' => [
                    ['buyer' => $buyer]
                ]

            ],
            '$addToSet' => [
                'history' => [
                    'po_history' => [unserialize($po_history)],

                ],

            ],


        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


        $dataPO = $collection->aggregate([
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

        $dataPOLog = serialize($dataPO);



        $collectionLog = Yii::$app->mongo->getCollection('log');
        $collectionLog->insert([
            'status' => 'PO Completed',
            'date_po_completed' => date('Y-m-d h:i:s'),
            unserialize($dataPOLog)

        ]);

        $notify = Notification::find()->where(['project_id'=>$newProject_id])->one();

        $notify->status_buyer = 'Solve';
        $notify->status_approver = 'Solve';

        $notify->save();





        return $this->redirect(['order/index']);


    }





}