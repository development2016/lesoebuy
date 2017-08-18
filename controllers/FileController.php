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

        $project_no = $file[0]['project_no'];

        $model = $file[0]['sellers'][0]['direct_purchase'];




        return $this->render('index',[
        	'model' => $model,
            'newProject_id' => $newProject_id,
            'project_no' => $project_no
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
            echo "<embed src='".$file."' style='width:100%;height:100%;' >";
        }
    
    }


    public function actionDelete($path,$project)
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

                    ],
                    
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 


        $arrUpdate = [
            '$pull' => [
                'sellers.0.direct_purchase.0.filename' => [
                    'path' => $path

                ]

            ]

        ];
        $collection->update(['_id' => $newProject_id],$arrUpdate); 

        $data = Yii::getAlias('@webroot/offline/'.$path);
        unlink($data);

        Yii::$app->getSession()->setFlash('remove_file', 'Your File Has Been Delete');
        return $this->redirect(['file/index','project'=>(string)$newProject_id]);


    }

    public function actionUpload($project,$project_no)
    {


        $model = new UploadForm();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();

        $company = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $date_today = date('Ymd');

        $uploader = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();


        $path_to_user_dir = Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name.'/'.$project_no);

        $count = count(glob("$path_to_user_dir/*"));

        if (Yii::$app->request->isPost) {

            if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id))) {
                mkdir(Yii::getAlias('@webroot/offline/'.$company->_id), 0777, true);
            }
            


            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {   

                $i = 0;
                if ($count == 0) {

                    $count++;

                } else {

                    $count++;

                }


                if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name.'/'.$project_no))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name.'/'.$project_no), 0777, true);
                }




                $model->file->saveAs(Yii::getAlias('@webroot/offline/'.$company->_id).'/'.'direct-purchase/'.$date_today.'/'.$uploader->account_name.'/'.$project_no.'/'.$count.'-'.$date_today.'.'.$model->file->extension);


                $file = $count.'-'.$date_today.'.'.$model->file->extension;
                $path = $company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name.'/'.$project_no.'/'.$file;
                $ext = $model->file->extension;
                $date_create = date('Y-m-d');

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [

                '$addToSet' => [
                    'sellers.0.direct_purchase.0.filename' => [
                        
                            'file' => $file,
                            'path' => $path,
                            'ext' => $ext,
                            'date_create' => $date_create,

                        
                        

                    ],

                ],

            
            ];
            $collection->update(['_id' => (string)$project],$arrUpdate);

            Yii::$app->getSession()->setFlash('uploaded', 'Your File Succesfully Uploaded');
            return $this->redirect(['file/index','project'=>(string)$project]);




            }


        }

        return $this->renderAjax('upload', [
            'model' => $model,
        ]);






    }







}