<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CompanyOffline;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\Company;
use app\models\AsiaebuyCompany;
use app\models\UserCompany;
use app\models\User;
use app\models\LookupState;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Uploads;
use yii\data\ActiveDataProvider;

class OfflineController extends Controller
{

	public function actionIndex()
	{	

        $model3 = new Project();

        $requester_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();


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


        $combine = $info_role.$info_role_2;

        if ($model3->load(Yii::$app->request->post()) ) {

            $getP = Project::find()->orderBy(['_id' => SORT_DESC])->limit(1)->one();

                if (empty($getP['project_no'])) {

                    $runninNo = 1000;
                    $day = date('d');
                    $month = date('m');
                    $year = date('y');
                    $project_no  = 'MY'.$day.$month.$year.$runninNo;


                } else {

                    $qt = substr($getP['project_no'], 8);
                    $new = $qt + 1;
                    $runninNo = $new;

                    $day = date('d');
                    $month = date('m');
                    $year = date('y');
                    $project_no  = 'MY'.$day.$month.$year.$runninNo;

                }

                $model3->project_no = $project_no;
                $model3->type_of_project = 'Direct Purchase';
                $model3->date_create = date('Y-m-d h:i:s');
                $model3->enter_by = Yii::$app->user->identity->id;
                $model3->requester = $requester_id->account_name;
                $model3->request_role = $combine;
                $model3->buyers = [
                
                    [
                        'buyer' => $requester_id->account_name
                    ]
                ];
                $model3->sellers = [
                    [
                        'status' => 'In Process'
                    ]
                ];

                $model3->save();
                

            Yii::$app->getSession()->setFlash('project', 'Project Has Been Submit');
            return $this->redirect(['offline/project','project' => $project_no]);

        } else {

            return $this->render('index',[

                'model3' => $model3,

            ]);

            
        }


	}


    public function actionProject($project)
    {

        $today = date('Y-m-d');
        $upload = new UploadForm();

        $model = new CompanyOffline();

        $customer = CompanyOffline::find()->all();

        $model2 = Uploads::find()->where([
                'enter_by'=> Yii::$app->user->identity->id,
                'date_create' => $today,
                'project_no' => $project,

            ]
        )
        ->all();

        $model3 = Project::find()->where(['project_no'=>$project])->one();

        if ($model3->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) ) {

            if (empty($_POST['Project']['document'])) {


                $model3->sellers = [[
                    'seller' => $_POST['CompanyOffline']['company_name'],
                    'company_registeration_no' => $_POST['CompanyOffline']['company_registeration_no'],
                    'address' => $_POST['CompanyOffline']['address'],
                    'zip_code' => $_POST['CompanyOffline']['zip_code'],
                    'country' => $_POST['CompanyOffline']['country'],
                    'state' => $_POST['CompanyOffline']['state'],
                    'city' => $_POST['CompanyOffline']['city'],
                    'telephone_no' => $_POST['CompanyOffline']['telephone_no'],
                    'fax_no' => $_POST['CompanyOffline']['fax_no'],
                    'email' => $_POST['CompanyOffline']['email'],
                    'website' => $_POST['CompanyOffline']['website'],
                    'tax' => $_POST['CompanyOffline']['tax'],
                    'type_of_tax' => $_POST['CompanyOffline']['type_of_tax'],
                    'term' => $_POST['CompanyOffline']['term'],
                    'status' => 'Project Created',
                    'direct_purchase' => [


                    ],
                    'warehouses' => [],
                    'purchase_requisition_no' => '',
                    'purchase_order_no' => '',
                    'items'=> [],


                ]];

                $model->date_create = date('Y-m-d H:i:s');
                $model->enter_by = Yii::$app->user->identity->id;


                
            } else {

                $document = serialize($_POST['Project']['document']);

                $model3->sellers = [[
                    'seller' => $_POST['CompanyOffline']['company_name'],
                    'company_registeration_no' => $_POST['CompanyOffline']['company_registeration_no'],
                    'address' => $_POST['CompanyOffline']['address'],
                    'zip_code' => $_POST['CompanyOffline']['zip_code'],
                    'country' => $_POST['CompanyOffline']['country'],
                    'state' => $_POST['CompanyOffline']['state'],
                    'city' => $_POST['CompanyOffline']['city'],
                    'telephone_no' => $_POST['CompanyOffline']['telephone_no'],
                    'fax_no' => $_POST['CompanyOffline']['fax_no'],
                    'email' => $_POST['CompanyOffline']['email'],
                    'website' => $_POST['CompanyOffline']['website'],
                    'tax' => $_POST['CompanyOffline']['tax'],
                    'type_of_tax' => $_POST['CompanyOffline']['type_of_tax'],
                    'term' => $_POST['CompanyOffline']['term'],
                    'status' => 'Project Created',
                    'direct_purchase' => [

                        unserialize($document)


                    ],
                    'warehouses' => [],
                    'purchase_requisition_no' => '',
                    'purchase_order_no' => '',
                    'items'=> [],


                ]];

                $model->date_create = date('Y-m-d H:i:s');
                $model->enter_by = Yii::$app->user->identity->id;

            }



                $model3->save() && $model->save();
                Uploads::deleteAll(['enter_by'=> Yii::$app->user->identity->id,'date_create'=>$today]);

            Yii::$app->getSession()->setFlash('direct', 'Your Direct Purchase Has Been Submit');
            return $this->redirect(['source/index']);

        } else {

            return $this->render('project',[
                'model' => $model,
                'model2' => $model2,
                'model3' => $model3,
                'upload' => $upload,
                'customer' => $customer,
                'project' => $project,
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


    public function actionUpload($project)
    {
        $model = new UploadForm();
        $model2 = new Uploads();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();

        $company = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $date_today = date('Ymd');

        $uploader = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();


        $path_to_user_dir = Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name);

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


                if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name), 0777, true);
                }

$model->file->saveAs(Yii::getAlias('@webroot/offline/'.$company->_id).'/'.'direct-purchase/'.$date_today.'/'.$uploader->account_name.'/'.$count.'-'.$date_today.'.'.$model->file->extension);


                $model2->filename = $count.'-'.$date_today.'.'.$model->file->extension;
                $model2->extension = $model->file->extension;
                $model2->path = '/direct-purchase'.'/'.$date_today.'/'.$uploader->account_name.'/';
                $model2->company_id = (string)$company->_id;
                $model2->enter_by = Yii::$app->user->identity->id;
                $model2->date_create = date('Y-m-d H:i:s');
                $model2->project_no = $project;
                
                
                $model2->save();

                return $this->redirect(['offline/project','project' => $project ]);


            }
            

        }

        return $this->renderAjax('upload', [
            'model' => $model,
        ]);
    }


    public function actionView($id,$filename,$project)
    {
        $model2 = Uploads::find()->where([
                'id'=>$id,
                'filename' => $filename
            ]
        )
        ->one();
        echo $model2->filename;
    }


    public function actionDelete($id,$filename,$path,$company_id,$project)
    {

        $file = $company_id.$path.$filename;
        $data = Yii::getAlias('@webroot/offline/'.$file);
        unlink($data);
        $this->findModel($id)->delete();



        return $this->redirect(['offline/project','project'=>$project]);
    }




    protected function findModel($id)
    {
        if (($model = Uploads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	
}