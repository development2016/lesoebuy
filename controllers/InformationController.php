<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\User;
use app\models\UserCompany;
use app\models\Company;
use app\models\ItemOffline;
use app\models\CompanyOffline;
use app\models\Log;

class InformationController extends Controller
{


    public function actionGet()
    {

        $posts = LookupState::find()
        ->where(['id' => $_POST['state_id']])
        ->all();


            foreach($posts as $post){
                echo "<option value='".$post->id."'>".$post->state."</option>";
            }
    }




	// guide buying
    public function actionGuideShippingCreate($project,$seller,$path)
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
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'shipping_price' => '$sellers.shipping_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 



        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['shipping_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'No',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }


            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

            

        } else {
            return $this->renderAjax('guide-shipping-create', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideShippingUpdate($project,$seller,$path)
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
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'shipping_price' => '$sellers.shipping_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['shipping_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'No',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }


        } else {
            return $this->renderAjax('guide-shipping-update', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideInstallationCreate($project,$seller,$path)
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
                    'buyer' => ['$first' => '$buyer'],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'installation_price' => '$sellers.installation_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['installation_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'No',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

        } else {
            return $this->renderAjax('guide-installation-create', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideInstallationUpdate($project,$seller,$path)
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
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'installation_price' => '$sellers.installation_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['installation_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'No',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

        } else {
            return $this->renderAjax('guide-installation-update', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }


    public function actionSaleItemUpdate($project,$seller,$path,$arrayItem,$approver)
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


        if ($model->load(Yii::$app->request->post())) {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.item_code' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['item_code'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.item_name' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['item_name'],
                        ]
                        
                    ]

                );


            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }
            

        } else {
            return $this->renderAjax('sale-item-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }

    public function actionSaleDetailUpdate($project,$seller,$path,$arrayItem,$approver)
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


        if ($model->load(Yii::$app->request->post())) {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.brand' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['brand'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.model' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['model'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.specification' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['specification'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.remark' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['remark'],
                        ]
                        
                    ]

                );

            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }


        } else {
            return $this->renderAjax('sale-detail-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }

    public function actionSaleInstallationUpdate($project,$seller,$path,$arrayItem,$approver)
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

       

        if ($model->load(Yii::$app->request->post())) {


            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.install' => 'No',
                            'sellers.$.items.'.$_POST['arrayItem'].'.installation_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.install' => 'Yes',
                                'sellers.$.items.'.$_POST['arrayItem'].'.installation_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }
            

        } else {
            return $this->renderAjax('sale-installation-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data,
            ]);
        }


    }

    public function actionSaleShippingUpdate($project,$seller,$path,$arrayItem,$approver)
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



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.shipping' => 'No',
                            'sellers.$.items.'.$_POST['arrayItem'].'.shipping_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.shipping' => 'Yes',
                                'sellers.$.items.'.$_POST['arrayItem'].'.shipping_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }

            

        } else {
            return $this->renderAjax('sale-shipping-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' =>$data,
            ]);
        }


    }




    public function actionSaleQuantityUpdate($project,$seller,$path,$arrayItem,$approver)
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



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.quantity' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.quantity' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'],
                            ]
                        
                    ]

                );

            }

            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }



        } else {
            return $this->renderAjax('sale-quantity-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }


    public function actionSaleCostUpdate($project,$seller,$path,$arrayItem,$approver)
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



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.cost' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.cost' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }
            


        } else {
            return $this->renderAjax('sale-cost-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data,
            ]);
        }


    }

    public function actionSaleRemove($seller,$project,$path,$arrayItem,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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



        $arrUpdate = [
            '$pull' => [
                'sellers.$.items' => [
                    'item_id' => (int)$arrayItem

                ]

               /* [
                    'item_id' => (int)$item_id
                ] */

            ]

        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
   
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
                
            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }


        
    }


    public function actionAddDelivery($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        if (empty($model['sellers'][0]['warehouses'][0]['country'])) {

            $country = 0;
            
        } else {
            $country = $model['sellers'][0]['warehouses'][0]['country'];
        }

        if (empty($model['sellers'][0]['warehouses'][0]['state'])) {
            $state = 0;
        } else {
            $state = $model['sellers'][0]['warehouses'][0]['state'];
        }
 

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if ($model->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.warehouses' => [[
                            'person_in_charge' => $_POST['Project']['sellers']['warehouses']['person_in_charge'],
                            'contact' => $_POST['Project']['sellers']['warehouses']['contact'],
                            'email' => $_POST['Project']['sellers']['warehouses']['email'],
                            'country' => $_POST['Project']['sellers']['warehouses']['country'],
                            'state' => $_POST['Project']['sellers']['warehouses']['state'],
                            'location' => $_POST['Project']['sellers']['warehouses']['location'],
                            'warehouse_name' => $_POST['Project']['sellers']['warehouses']['warehouse_name'],
                            'address' => $_POST['Project']['sellers']['warehouses']['address'],
                            'latitude' => $_POST['Project']['sellers']['warehouses']['latitude'],
                            'longitude' => $_POST['Project']['sellers']['warehouses']['longitude'],


                        ]]

                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
           
               
            } elseif ($path == 'direct') {

                 return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer,'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer]);

            } else if ($path == 'resubmitnext') {
                
                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
            }


            

        } else {

            return $this->renderAjax('add-delivery',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer,
                'country' => $country,
                'state' => $state
            ]);
        }


    }


    public function actionEditDelivery($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();



        if (empty($model['sellers'][0]['warehouses'][0]['country'])) {

            $country = 0;
            
        } else {
            $country = $model['sellers'][0]['warehouses'][0]['country'];
        }

        if (empty($model['sellers'][0]['warehouses'][0]['state'])) {
            $state = 0;
        } else {
            $state = $model['sellers'][0]['warehouses'][0]['state'];
        }
 

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if ($model->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.warehouses' => [[
                            'person_in_charge' => $_POST['Project']['sellers']['warehouses']['person_in_charge'],
                            'contact' => $_POST['Project']['sellers']['warehouses']['contact'],
                            'email' => $_POST['Project']['sellers']['warehouses']['email'],
                            'country' => $_POST['Project']['sellers']['warehouses']['country'],
                            'state' => $_POST['Project']['sellers']['warehouses']['state'],
                            'location' => $_POST['Project']['sellers']['warehouses']['location'],
                            'warehouse_name' => $_POST['Project']['sellers']['warehouses']['warehouse_name'],
                            'address' => $_POST['Project']['sellers']['warehouses']['address'],
                            'latitude' => $_POST['Project']['sellers']['warehouses']['latitude'],
                            'longitude' => $_POST['Project']['sellers']['warehouses']['longitude'],


                        ]]

                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
           
               
            } elseif ($path == 'direct') {

                 return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer,'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer]);

            } else if ($path == 'resubmitnext') {
                
                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
            }


            

        } else {

            return $this->renderAjax('edit-delivery',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer,
                'country' => $country,
                'state' => $state
            ]);
        }


    }


    public function actionItem($seller,$project,$path,$approver)
    {
        $offline = new ItemOffline();

        $data = ItemOffline::find()->all();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $process = $collection->aggregate([
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
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        $items = $process[0]['sellers'][0]['items'];

        $countitem = count($items); // this to count how many items for specific sellers */



        if ($model->load(Yii::$app->request->post())) {

            if ($countitem == 0) {


                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();


                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->shipping =  0;
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();



                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();





                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  0;
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();


                }

            } else {

                $up = $countitem++;
            

                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [

                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  0;
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();





                } else {



                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );

                    $offline->item_code =  $_POST['Project']['sellers']['items']['item_code'];
                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    //$offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    //$offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  0;
                    $offline->remark =  $_POST['Project']['sellers']['items']['remark'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




                }




            }

            if ($path == 'source') {

                return $this->redirect([
                    'source/direct-purchase-requisition', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);

       
            } elseif ($path == 'request') {

                return $this->redirect([
                    'request/direct-purchase-requisition-resubmit', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);

            } elseif ($path == 'check') {

                return $this->redirect([
                    'request/direct-purchase-requisition-check', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);
                
            } else if ($path == 'revise') {

                return $this->redirect([
                    'request/direct-purchase-order-revise', 
                    'project' =>(string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer']
                ]);

            } elseif ($path == 'resubmitnext') {

                return $this->redirect([
                    'request/direct-purchase-requisition-resubmit-next', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);
            }


  
        } else {
            return $this->renderAjax('item', [
                'model' => $model,
                'data' => $data,
            ]);
        }
    }



    public function actionItemTemp($seller,$project,$path,$approver)
    {
        //$offline = new ItemOffline();

        $data = ItemOffline::find()->all();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $process = $collection->aggregate([
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
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        $items = $process[0]['sellers'][0]['items'];

        $countitem = count($items); // this to count how many items for specific sellers */



        if ($model->load(Yii::$app->request->post())) {

            if ($countitem == 0) {


                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);




                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_id' => 0,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



                }

            } else {

                $up = $countitem++;
            

                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [

                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );



                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );




                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );




                } else {



                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_id' => $up,
                                    'item_code' => $_POST['Project']['sellers']['items']['item_code'],
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    //'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    //'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                    'remark' => $_POST['Project']['sellers']['items']['remark'],
                                
                                ]
                            ]
                        ]

                    );





                }




            }

            if ($path == 'source') {

                return $this->redirect([
                    'source/direct-purchase-requisition', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);

       
            } elseif ($path == 'request') {

                return $this->redirect([
                    'request/direct-purchase-requisition-resubmit', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);

            } elseif ($path == 'check') {

                return $this->redirect([
                    'request/direct-purchase-requisition-check', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);
                
            } else if ($path == 'revise') {

                return $this->redirect([
                    'request/direct-purchase-order-revise', 
                    'project' =>(string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer']
                ]);

            } elseif ($path == 'resubmitnext') {

                return $this->redirect([
                    'request/direct-purchase-requisition-resubmit-next', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyers'][0]['buyer'],
                    'approver' => $approver
                ]);
            }


  
        } else {
            return $this->renderAjax('item-temp', [
                'model' => $model,
                'data' => $data,
            ]);
        }
    }




    public function actionCancelPr($seller,$project,$approver,$buyer,$path)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $projectModel = Project::find()->where(['_id'=>$newProject_id])->one();

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


        if ($projectModel->load(Yii::$app->request->post()) ) {

            $collection->update(
                ['_id' => $newProject_id,'sellers.seller' => $seller],
                [
                    
                        '$set' => [
                            'sellers.$.status' => 'PR Cancel',

                        ],
                        '$addToSet' => [
                            'pr_cancel' => [
                                'remark' => $_POST['Project']['sellers']['remark'],
                                'pr_no' => $data[0]['sellers'][0]['purchase_requisition_no'],

                            ]

                        ]
                    
                ]

            );

            $dataCancel = $collection->aggregate([
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

            $dataCancelLog = serialize($dataCancel);



            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Cancel PR',
                'date_cancel_pr' => date('Y-m-d h:i:s'),
                'by'  => $buyer,
                'project_no' => $dataCancel[0]['project_no'],
                'seller' => $dataCancel[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataCancel[0]['sellers']['purchase_requisition_no'],
                unserialize($dataCancelLog)

            ]);




            if ($path == 'source') {

                return $this->redirect(['source/index']);
                
            } elseif ($path == 'request') {

                return $this->redirect(['request/index']);

            } elseif ($path == 'check') {

                return $this->redirect(['request/index']);
            }

        } else {

            return $this->renderAjax('/source/cancel',[
                'projectModel' => $projectModel,


            ]);



        }
    
   
    }


    public function actionAddBefore($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if ($model->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.delivery_before' => $_POST['Project']['sellers']['delivery_before'],

                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
           
               
            } elseif ($path == 'direct') {

                 return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer,'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer]);

            } else if ($path == 'resubmitnext') {
                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
            }
            

        } else {

            return $this->renderAjax('add-before',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer
            ]);
        }


    }


    public function actionEditBefore($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if ($model->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.delivery_before' => $_POST['Project']['sellers']['delivery_before'],

                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'request') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
           
               
            } elseif ($path == 'direct') {

                 return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer,'approver'=>$approver]);

            } else if ($path == 'revise') {

                return $this->redirect(['request/direct-purchase-order-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $buyer]);

            } else if ($path == 'resubmitnext') {
                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
            }
            

        } else {

            return $this->renderAjax('edit-before',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer
            ]);
        }


    }






    public function actionAddCompany($project,$seller,$buyer,$path,$approver)
    {


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $modelCompany = new CompanyOffline();

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $CompanyOffline = CompanyOffline::find()->all();

        if ($modelCompany->load(Yii::$app->request->post())) {

            $modelCompany->date_create = date('Y-m-d H:i:s');
            $modelCompany->enter_by = Yii::$app->user->identity->id;
            $modelCompany->save();

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.seller' => $_POST['CompanyOffline']['company_name'],
                        'sellers.$.company_registeration_no' => $_POST['CompanyOffline']['company_registeration_no'],
                        'sellers.$.address' => $_POST['CompanyOffline']['address'],
                        'sellers.$.zip_code' => $_POST['CompanyOffline']['zip_code'],
                        'sellers.$.country' => $_POST['CompanyOffline']['country'],
                        'sellers.$.state' => $_POST['CompanyOffline']['state'],
                        'sellers.$.city' => $_POST['CompanyOffline']['city'],
                        'sellers.$.telephone_no' => $_POST['CompanyOffline']['telephone_no'],
                        'sellers.$.fax_no' => $_POST['CompanyOffline']['fax_no'],
                        'sellers.$.email' => $_POST['CompanyOffline']['email'],
                        'sellers.$.website' => $_POST['CompanyOffline']['website'],
                        'sellers.$.type_of_tax' => $_POST['CompanyOffline']['type_of_tax'],
                        'sellers.$.tax' => $_POST['CompanyOffline']['tax'],
                        'sellers.$.term' => $_POST['CompanyOffline']['term'],



                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'resubmit') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            }



            

        } else {

            return $this->renderAjax('add-company',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer,
                'modelCompany' => $modelCompany,
                'CompanyOffline' => $CompanyOffline
            ]);
        }


    }



    public function actionEditCompany($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $modelCompany = new CompanyOffline();

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


        $CompanyOffline = CompanyOffline::find()->all();


  
        if ($modelCompany->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.seller' => $_POST['CompanyOffline']['company_name'],
                        'sellers.$.company_registeration_no' => $_POST['CompanyOffline']['company_registeration_no'],
                        'sellers.$.address' => $_POST['CompanyOffline']['address'],
                        'sellers.$.zip_code' => $_POST['CompanyOffline']['zip_code'],
                        'sellers.$.country' => $_POST['CompanyOffline']['country'],
                        'sellers.$.state' => $_POST['CompanyOffline']['state'],
                        'sellers.$.city' => $_POST['CompanyOffline']['city'],
                        'sellers.$.telephone_no' => $_POST['CompanyOffline']['telephone_no'],
                        'sellers.$.fax_no' => $_POST['CompanyOffline']['fax_no'],
                        'sellers.$.email' => $_POST['CompanyOffline']['email'],
                        'sellers.$.website' => $_POST['CompanyOffline']['website'],
                        'sellers.$.type_of_tax' => $_POST['CompanyOffline']['type_of_tax'],
                        'sellers.$.tax' => $_POST['CompanyOffline']['tax'],
                        'sellers.$.term' => $_POST['CompanyOffline']['term'],



                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'check') {

                return $this->redirect(['request/direct-purchase-requisition-check','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'resubmit') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            } else if ($path == 'resubmitnext') {

                return $this->redirect(['request/direct-purchase-requisition-resubmit-next','project'=>(string)$project,'seller'=>$_POST['CompanyOffline']['company_name'],'approver'=>$approver,'buyer'=>$buyer]);

               
            }



            

        } else {

            return $this->renderAjax('edit-company',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer,
                'modelCompany' => $modelCompany,
                'CompanyOffline' => $CompanyOffline
            ]);
        }


    }



    public function actionRevisePo($seller,$project,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $projectModel = Project::find()->where(['_id'=>$newProject_id])->one();

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
        $data_revise = serialize($data[0]['sellers']);

        

        if ($projectModel->load(Yii::$app->request->post()) ) {


            if (empty($data[0]['sellers'][0]['count_revise'])) {

                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            
                                '$set' => [
                                    'sellers.$.status' => 'PO Revise',
                                    'sellers.$.count_revise' => 1,
                                    'sellers.$.revise_status' => 'Progress',
                                    'sellers.$.date_revise_po' => date('Y-m-d h:i:s'),

                                ],
                                '$addToSet' => [
                                    'revise_po' => [
                                        'remark' => $_POST['Project']['sellers']['remark'],
                                        'po_no' => $data[0]['sellers'][0]['purchase_order_no'],
                                        'data_revise' => unserialize($data_revise)

                                    ]

                                ]
                            
                        ]

                    );

            } else {

                $new = $data[0]['sellers'][0]['count_revise']+1;

                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.status' => 'PO Revise',
                                'sellers.$.count_revise' => $new,
                                'sellers.$.revise_status' => 'Progress',
                                'sellers.$.date_revise_po' => date('Y-m-d h:i:s'),

                            ],
                            '$addToSet' => [
                                'revise_po' => [
                                    'remark' => $_POST['Project']['sellers']['remark'],
                                    'po_no' => $data[0]['sellers'][0]['purchase_order_no'],
                                    'data_revise' => unserialize($data_revise)

                                ]

                            ]
                        
                    ]

                );



            }





            $dataRevise = $collection->aggregate([
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

            $dataReviseLog = serialize($dataRevise);



            $collectionLog = Yii::$app->mongo->getCollection('log');
            $collectionLog->insert([
                'status' => 'Revise PO',
                'date_revise_po' => date('Y-m-d h:i:s'),
                'by' => $buyer,
                'project_no' => $dataRevise[0]['project_no'],
                'seller' => $dataRevise[0]['sellers']['seller'],
                'purchase_requisition_no' => $dataRevise[0]['sellers']['purchase_requisition_no'],
                'purchase_order_no' => $dataRevise[0]['sellers']['purchase_order_no'],
                unserialize($dataReviseLog)

            ]);

            return $this->redirect([
                'request/direct-purchase-order-revise', 
                'project' => (string)$newProject_id,
                'seller'=>$seller,
                'buyer' => $buyer,
                ]);





        } else {

            return $this->renderAjax('/request/revise',[
                'projectModel' => $projectModel,


            ]);


        }



    }






}