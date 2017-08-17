<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\User;
use app\models\Password;
use app\models\Acl;
use app\models\UserCompany;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\models\Project;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
 /*   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
 */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['error','signup','login','request-password-reset','reset-password','state','register','seller','buyer','comming','company-name','registeration-no','username','idle','online','tutorial','print'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','contact','history'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }



    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionPrint()
    {

        $this->layout = 'print';

        return $this->render('print');
    }




    public function actionTutorial()
    {

        return $this->render('tutorial');

    }


    public function actionIdle()
    {

        $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $user->status_login = $_POST['idle'];
        $user->save();

    }

    public function actionOnline()
    {

        $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $user->status_login = $_POST['online'];
        $user->save();

    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();


        $online = User::find()->where('id != :id and username != :username and status_login = :status_login', ['id'=>Yii::$app->user->identity->id, 'username'=>'admin','status_login'=>1])->all();

        $offline = User::find()->where('id != :id and username != :username and status_login = :status_login', ['id'=>Yii::$app->user->identity->id, 'username'=>'admin','status_login'=>0])->all();


        $idle = User::find()->where('id != :id and username != :username and status_login = :status_login', ['id'=>Yii::$app->user->identity->id, 'username'=>'admin','status_login'=>3])->all();



         $connection = \Yii::$app->db;
         $sql = $connection->createCommand('SELECT lookup_role.role AS role FROM acl 
          RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
          RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
          RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
          WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
        $getRole = $sql->queryAll(); 

            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                        return true;
                    }
                }

                return false;
            }

        $info_role = in_array_r('Buyer', $getRole) ? 'Buyer' : '';

        $info_role_2 = in_array_r('User', $getRole) ? 'User' : '';

        $info_role_3 = in_array_r('Approval', $getRole) ? 'Approval' : ''; // incase buyer as approval

        // checking role
        if ($info_role_3 == 'Approval' && $info_role == 'Buyer') {

            $role = 'ApprovalBuyer';
           
        } elseif ($info_role_2 == 'User' && $info_role == 'Buyer') {
            
            $role ='UserBuyer'; // done

        } elseif ($info_role == 'Buyer') {
            
            $role ='Buyer';

        } elseif ($info_role_3 == 'Approval') {

           $role ='Approval'; // done

        } elseif ($info_role_2 == 'User') {

           $role ='User'; // done
        }
 


        $collection = Yii::$app->mongo->getCollection('project');
        $totalPO = $collection->aggregate([
            [
                '$match' => [
                    '$and' => [
                            [
                                'sellers.status' => 'PO Completed'
                            ],
                    ],
                ]
            ],
            [
                '$group' => [
                    '_id' => '$requester',
                    'count' => [
                        '$sum' => 1
                    ],

            
                ]
            ],

        ]);

/*
        $totalmyPO = $collection->aggregate([
          [
                '$match' => [
                    '$and' => [
                            [
                                'sellers.status' => 'PO Completed'
                            ],
                            [
                                 'buyers.buyer' => $user->account_name
                            ]
                    ],
                ]
            ],
            [
                '$project' => [

                    'yearMonthDay' => [
                        '$dateToString' => [
                            'format' => '%Y-%m-%d',
                            'date' => '$date_create'
                        ]
                    ]

                ]

            ],

            [
                '$group' => [
                    '_id' => '$yearMonthDay',

                    
                    'count' => [
                        '$sum' => 1
                    ],

            
                ]
            ],

        ]);

        print_r($totalmyPO);

        


        exit();*/










        $current = date('Y-m-d');

        if ($role == 'UserBuyer' || $role == 'User') {

            $overdue = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [
                                [
                                    'sellers.status' => 'Request Approval'
                                ],
                                [
                                    'due_date' => [
                                        '$lt' => $current
                                    ]
                                ],
                                [
                                     'buyers.buyer' => $user->account_name
                                ]
                        ],
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);

            $sum_overdue =0;
            foreach ($overdue as $key_overdue => $value_overdue) {
                $sum_overdue += $value_overdue['count'];
            }
      
        

            $approve = $collection->aggregate([
                    [
                        '$match' => [
                            '$and' => [
                                    [
                                        'sellers.status' => 'Approve'
                                    ],
                                    [
                                        'buyers.buyer' => $user->account_name
                                    ]
          
                            ],
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => '$project_no',
                            'count' => [
                                '$sum' => 1
                            ],

                    
                        ]
                    ],

            ]);
            $sum_approve =0;
            foreach ($approve as $key_approve => $value_approve) {
                $sum_approve += $value_approve['count'];
            }

            $collection_notification = Yii::$app->mongo->getCollection('notification');
            $notification = $collection_notification->aggregate([
                [
                    '$unwind' => '$to_who'
                ],
                [
                    '$match' => [
                        '$and' => [

                            [
                                'to_who' => $user->account_name
                            ],
                            [
                                'read_unread' => 0
                            ]
                            

                        ],
                        '$or' => [
                            [
                                'status_approver' => 'Waiting Approval'
                            ],
                            [
                                'status_approver' => 'Approve'
                            ],
                            [
                                'status_approver' => 'Next Approver'
                            ],
                            [
                                'status_buyer' => 'Change Buyer'
                            ],
                            [
                                'status_approver' => 'Reject PR'
                            ],
                            [
                                'status_approver' => 'Resubmit Approval'
                            ],
                            [
                                'status_from_buyer' => 'Reject PR'
                            ]

                        ]
                        
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],


      
            ]);


            $sum_pending =0;
            foreach ($notification as $key_notification => $value_notification) {
                $sum_pending += $value_notification['count'];
            }


            $process = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [

                                [
                                     'buyers.buyer' => $user->account_name
                                ]
                        ],
                        '$or' => [

                                [
                                    'sellers.status' => 'Request Approval'
                                ],

                                [
                                    'sellers.status' => 'Reject PR'

                                ],
                                [
                                    'sellers.status' => 'Approve'

                                ]


                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);

            $sum_process =0;
            foreach ($process as $key_process => $value_process) {
                $sum_process += $value_process['count'];
            }




            



        } elseif ($role == 'Approval') {
            
            $overdue = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [

                                [
                                    'due_date' => [
                                        '$lt' => $current
                                    ]
                                ],
                                [
                                    'sellers.approval.approval' => $user->account_name
                                ]
      
                        ],
                        '$or' => [
                                [
                                    'sellers.status' => 'Request Approval'
                                ],
                                [
                                    'sellers.status' => 'Request Approval Next'
                                ],


                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);
            $sum_overdue =0;
            foreach ($overdue as $key_overdue => $value_overdue) {
                $sum_overdue += $value_overdue['count'];
            }
      

            $approve = $collection->aggregate([
                    [
                        '$match' => [
                            '$and' => [

                                    [
                                        'sellers.approval.approval' => $user->account_name
                                    ]
          
                            ],
                            '$or' => [
                                    [
                                        'sellers.status' => 'Approve'
                                    ],
                                    [
                                        'sellers.status' => 'Approve Next'
                                    ],
                            ]
                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => '$project_no',
                            'count' => [
                                '$sum' => 1
                            ],

                    
                        ]
                    ],

            ]);

            $sum_approve =0;
            foreach ($approve as $key_approve => $value_approve) {
                $sum_approve += $value_approve['count'];
            }

            $collection_notification = Yii::$app->mongo->getCollection('notification');
            $notification = $collection_notification->aggregate([
                [
                    '$unwind' => '$to_who'
                ],
                [
                    '$match' => [
                        '$and' => [

                            [
                                'to_who' => $user->account_name
                            ],
                            [
                                'read_unread' => 0
                            ]
                            

                        ],
                        '$or' => [
                            [
                                'status_approver' => 'Waiting Approval'
                            ],
                            [
                                'status_approver' => 'Approve'
                            ],
                            [
                                'status_approver' => 'Next Approver'
                            ],
                            [
                                'status_buyer' => 'Change Buyer'
                            ],
                            [
                                'status_approver' => 'Reject PR'
                            ],
                            [
                                'status_approver' => 'Resubmit Approval'
                            ],
                            [
                                'status_from_buyer' => 'Reject PR'
                            ]

                        ]
                        
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],


      
            ]);


            $sum_pending =0;
            foreach ($notification as $key_notification => $value_notification) {
                $sum_pending += $value_notification['count'];
            }

            $process = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [

                                [
                                    'sellers.approval.approval' => $user->account_name
                                ]
                        ],
                        '$or' => [

                                [
                                    'sellers.status' => 'Request Approval'
                                ],

                                [
                                    'sellers.status' => 'Request Approval Next'
                                ]

                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);

            $sum_process =0;
            foreach ($process as $key_process => $value_process) {
                $sum_process += $value_process['count'];
            }






        } elseif ($role == 'ApprovalBuyer' || $role == 'Buyer') {

            $overdue = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [

                                [
                                    'due_date' => [
                                        '$lt' => $current
                                    ]
                                ],
                                [
                                    'buyers.buyer' => $user->account_name
                                ]
      
                        ],
                        '$or' => [
                                [
                                    'sellers.status' => 'Pass PR to Buyer To Proceed PO'
                                ],
                                [
                                    'sellers.status' => 'Request Approval Next'
                                ]


                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);

            $sum_overdue =0;
            foreach ($overdue as $key_overdue => $value_overdue) {
                $sum_overdue += $value_overdue['count'];
            }


            $approve = $collection->aggregate([
                    [
                        '$match' => [
                            '$and' => [

                                    [
                                        'buyers.buyer' => $user->account_name
                                    ]
          
                            ],
                            '$or' => [
                                    [
                                        'sellers.status' => 'Approve'
                                    ],
                                    [
                                        'sellers.status' => 'Approve Next'
                                    ],
                            ]



                        ]
                    ],
                    [
                        '$group' => [
                            '_id' => '$project_no',
                            'count' => [
                                '$sum' => 1
                            ],

                    
                        ]
                    ],

            ]);

            $sum_approve =0;
            foreach ($approve as $key_approve => $value_approve) {
                $sum_approve += $value_approve['count'];
            }

            $collection_notification = Yii::$app->mongo->getCollection('notification');
            $notification = $collection_notification->aggregate([
                [
                    '$unwind' => '$to_who'
                ],
                [
                    '$match' => [
                        '$and' => [

                            [
                                'to_who' => $user->account_name
                            ],
                            [
                                'read_unread' => 0
                            ]
                            

                        ],
                        '$or' => [
                            [
                                'status_approver' => 'Waiting Approval'
                            ],
                            [
                                'status_approver' => 'Approve'
                            ],
                            [
                                'status_approver' => 'Next Approver'
                            ],
                            [
                                'status_buyer' => 'Change Buyer'
                            ],
                            [
                                'status_approver' => 'Reject PR'
                            ],
                            [
                                'status_approver' => 'Resubmit Approval'
                            ],
                            [
                                'status_from_buyer' => 'Reject PR'
                            ]

                        ]
                        
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],


      
            ]);


            $sum_pending =0;
            foreach ($notification as $key_notification => $value_notification) {
                $sum_pending += $value_notification['count'];
            }





            $process = $collection->aggregate([
                [
                    '$match' => [
                        '$and' => [

                                [
                                     'buyers.buyer' => $user->account_name
                                ]
                        ],
                        '$or' => [

                                [
                                    'sellers.status' => 'Pass PR to Buyer To Proceed PO'
                                ],
                                [
                                    'sellers.status' => 'Request Approval Next'
                                ],
                                [
                                    'sellers.status' => 'Reject Next'
                                ],
                                [
                                    'sellers.status' => 'Approve Next'
                                ],


                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_no',
                        'count' => [
                            '$sum' => 1
                        ],

                
                    ]
                ],

            ]);

            $sum_process =0;
            foreach ($process as $key_process => $value_process) {
                $sum_process += $value_process['count'];
            }



        }


        return $this->render('index',[
            'online' => $online,
            'offline' => $offline,
            'idle' => $idle,
            'totalPO' => $totalPO,
            'sum_overdue' => $sum_overdue,
            'sum_approve' => $sum_approve,
            'sum_pending' => $sum_pending,
            'sum_process' => $sum_process,
            'role' => $role,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

            if ($user->status_login > 0) {

                $user->status_login = $user->status_login + 1;
                Yii::$app->getSession()->setFlash('another', 'Another User Using Your Account');
   
            } else {

                $user->status_login = 1;
            }

        
            $user->save();

            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionComming()
    {
        $this->layout = 'login';
        return $this->render('comming');
    }



    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {

        $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $user->status_login = 0;

        $user->save();


        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {

        $this->layout = 'login';

        return $this->render('register');
    }

    public function actionBuyer()
    {

        $model = new User();
        $model2 = new Company();
        $model3 = new Password();
        $model6 = new Acl();
        $model7 = new UserCompany();

        $this->layout = 'register';

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) ) {

            $state = LookupState::find()->where(['id'=>$_POST['Company']['state']])->one();
            $country = LookupCountry::find()->where(['id'=>$_POST['Company']['country']])->one();

            $getR = Company::find()->where(['type'=>'Buyer'])->orderBy(['_id' => SORT_DESC])->limit(1)->one();
            //check wheter ASIAEBUY id have it or not , if not start with 1000 after that +1

            if (empty($getR['asia_ebuy_no'])) {

                $runninNo = 1000;
                $asiano = 'Buyer'.$runninNo;
                
            } else {

                $qt = substr($getR['asia_ebuy_no'], 5);
                $new = $qt + 1;
                $runninNo = $new;

                $asiano = 'Buyer'.$runninNo;
            }

            $model->as_a = 300; // as buyer
            $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
            $model->account_name = $_POST['User']['username'].'@'.$asiano;
            $model->status_login = 0;
            
            if ($model->save()) {

                $last_id = Yii::$app->db->getLastInsertID();

                $model2->company_name = strtoupper($_POST['Company']['company_name']);
                $model2->type = "Buyer";
                $model2->date_create = date('Y-m-d h:i:s');
                $model2->email = $_POST['User']['email'];
                $model2->admin = (int)$last_id;
                $model2->asia_ebuy_no = $asiano.'@'.$_POST['Company']['city'].','.$state->state.'.'.$country->code;
                $model2->warehouses = [];
                $model2->status = "In Progress";


                $model2->save();

                foreach ($model2->primaryKey as $key => $value) {
                    $mongo_id = $value;
                }


                $model7->user_id = (int)$last_id;
                $model7->company = $mongo_id;
                $model7->save();

                $model3->password = $_POST['User']['password_hash'];
                $model3->id_user = (int)$last_id;


                $connection = \Yii::$app->db;
                $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE role_id = 3300');
                $sql_model = $sql->queryAll();


                foreach ($sql_model as $key_acl => $value_acl) { 

                    $model6 =new Acl();
                    $model6->acl_menu_id = $value_acl['id'];
                    $model6->user_id = (int)$last_id;
                    $model6->company_id = $mongo_id;
                    $model6->save();
                   
                }

                $model3->save();

            }
            
            return $this->redirect('login');


        } else {

            return $this->render('buyer',[
                'model' => $model,
                'model2' => $model2,
            ]);

        }



    }


    public function actionSeller()
    {
        $model = new User();
        $model2 = new Company();
        $model3 = new Password();
        $model6 = new Acl();
        $model7 = new UserCompany();

        $this->layout = 'seller';

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) ) {

            $state = LookupState::find()->where(['id'=>$_POST['Company']['state']])->one();
            $country = LookupCountry::find()->where(['id'=>$_POST['Company']['country']])->one();

            $getR = Company::find()->where(['type'=>'Seller'])->orderBy(['_id' => SORT_DESC])->limit(1)->one();
            //check wheter ASIAEBUY id have it or not , if not start with 1000 after that +1

            if (empty($getR['asia_ebuy_no'])) {

                $runninNo = 1000;
                $asiano = 'Seller'.$runninNo;
                
            } else {

                $qt = substr($getR['asia_ebuy_no'], 6);
                $new = $qt + 1;
                $runninNo = $new;

                $asiano = 'Seller'.$runninNo;
            }

            $model->as_a = 200; // as seller
            $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
            $model->account_name = $_POST['User']['username'].'@'.$asiano;
            $model->status_login = 0;

            if ($model->save()) {


                $last_id = Yii::$app->db->getLastInsertID();

                $model2->type = "Seller";
                $model2->date_create = date('Y-m-d h:i:s');
                $model2->email = $_POST['User']['email'];
                $model2->admin = (int)$last_id;
                $model2->asia_ebuy_no = $asiano.'@'.$_POST['Company']['city'].','.$state->state.'.'.$country->code;
                $model2->warehouses = [];
                $model2->status = "In Progress";
                $model2->term = (int)$_POST['Company']['term'];

                $model2->save();

                foreach ($model2->primaryKey as $key => $value) {
                    $mongo_id = $value;
                }

                $model7->user_id = (int)$last_id;
                $model7->company = $mongo_id;
                $model7->save();

                $model3->password = $_POST['User']['password_hash'];
                $model3->id_user = (int)$last_id;

                $connection = \Yii::$app->db;
                $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE role_id = 2200');
                $sql_model = $sql->queryAll();


                foreach ($sql_model as $key_acl => $value_acl) { 

                    $model6 =new Acl();
                    $model6->acl_menu_id = $value_acl['id'];
                    $model6->user_id = (int)$last_id;
                    $model6->company_id = $mongo_id;
                    $model6->save();
                   
                }

                $model3->save();


            }

            return $this->redirect('login');



        } else {

            return $this->render('seller',[
                'model' => $model,
                'model2' => $model2,
            ]);

        }



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

    public function actionCompanyName()
    {


        if (!empty($_POST["value"])) {


            $company = Company::find()->where(['company_name'=>strtoupper($_POST['value'])])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Company Already Exist</span>';

            }

            

        } else {


        }
    }

    public function actionRegisterationNo()
    {


        if (!empty($_POST["value"])) {


            $company = Company::find()->where(['company_registeration_no'=>$_POST['value']])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Company Registeration No Already Exist</span>';

            }

            

        } else {


        }
    }

    public function actionUsername()
    {


        if (!empty($_POST["value"])) {


            $company = User::find()->where(['username'=>$_POST['value']])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Username Already Exist</span>';

            }

            

        } else {


        }
    }


    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {


            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

               
            } 
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            
            Yii::$app->session->setFlash('success', 'New password was saved. Please Logout to make a changes');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }






}
