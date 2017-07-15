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


class NotificationController extends Controller
{
    public function actionGet($id)
    {

        $model = $this->findModel($id);


        $model->read_unread = 1;
        $model->save();
        return $this->redirect([$model->url, 
        	'project' => (string)$model->project_id,
        	'seller'=>$model->seller,
        	'buyer'=>$model->from_who,
        	'approver'=>$model->approver,

        ]);





    }



    protected function findModel($id)
    {
        if (($model = Notification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}