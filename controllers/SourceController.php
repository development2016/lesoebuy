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
use app\models\ItemOffline;


class SourceController extends Controller
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
							'sellers' => [
								'$size' =>0
							]
						],
						[
							'sellers.status' => 'Quoted'
						],
						[
							'sellers.status' => 'Responded'
						],
						[
							'sellers.status' => 'Request'
						],
						[
							'sellers.status' => 'Waiting Quotation'
						],
						[
							'sellers.status' => 'Generate PR'
						],
                        [
                            'sellers.status' => 'Revise'
                        ],
                        [
                            'sellers.status' => 'Project Created'
                        ],
                        [
                            'sellers.status' => 'In Process'
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
                    'project_no' => ['$first' => '$project_no' ],
                    'buyers' => ['$first' => '$buyers'],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            'approver' => '$sellers.approver',

                            
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
                            'status' => 'Request Approval'
                        ],
                        [
                            'status' => 'Cancel PR'
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
                            'date_request' => '$date_request',
                            'date_cancel_pr' => '$date_cancel_pr',
                            'seller' => '$seller',
                            'purchase_requisition_no' => '$purchase_requisition_no',
                            'log_id' => '$_id',
                            'by' => '$by',
                            'project' => '$0',


                        ]
                    ],

                    
                ]
            ],


        ]);





        return $this->render('index',[
        	'model' => $model,
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
                    'buyers' => ['$first' => '$buyers' ],
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


        foreach ($list as $key => $value) {
           
            $purchase_requisition_no = $value['sellers'][0]['purchase_requisition_no'];
            $buyer = $value['buyers'][0]['buyer'];

        }

        if (empty($purchase_requisition_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseRequisitionNo = GeneratePurchaseRequisitionNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;

            if (empty($checkPurchaseRequisitionNo->purchase_requisition_no)) {

                $runninNo = 1000;
                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();

            } else {


                $pr = substr($checkPurchaseRequisitionNo->purchase_requisition_no, 2);
                $new = $pr + 1;
                $runninNo = $new;

                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_requisition_no' => $purchase_requisition_no,
                'sellers.$.date_purchase_requisition' => date('Y-m-d H:i:s'),

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
                        'sellers.$.approval' =>  $tempApp

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);
            


            return $this->redirect(['source/direct-purchase-requisition','project'=>$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>'normal']);
           
            



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


        $form = ActiveForm::begin(['action' =>['source/approver'], 'id' => 'forum_post', 'method' => 'post',]);

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

            ]
        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdate);




        return $this->redirect(['source/direct-purchase-requisition','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$_POST['buyer'],'approver'=>'level']);
    


    }



    public function actionState($id)
    {
        $countPosts = LookupState::find()
        ->where(['country_id' => $id])
        ->count();

        $posts = LookupState::find()
        ->where(['country_id' => $id])
        ->all();

        if($countPosts>0){
            echo "<option value=''>-Please Choose-</option>";
            foreach($posts as $post){
                echo "<option value='".$post->id."'>".$post->state."</option>";
            }
        } else {
                echo "<option></option>";
        }

    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGet()
    {

        $posts = LookupState::find()
        ->where(['id' => $_POST['state_id']])
        ->all();


            foreach($posts as $post){
                echo "<option value='".$post->id."'>".$post->state."</option>";
            }
    }


}