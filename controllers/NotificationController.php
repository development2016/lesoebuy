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

        if ($model->status_buyer == 'Active') {
     
            $model->status_approver = 'Pending';
            $model->read_unread = 1;
            $model->save();
            return $this->redirect([$model->url, 
                'project' => (string)$model->project_id,
                'seller'=>$model->seller,
                'buyer'=>$model->from_who,
                'approver'=>$model->approver,

            ]);

        } elseif ($model->status_buyer == 'Reject') {

            $model->read_unread = 1;
            $model->save();
            return $this->redirect([$model->url, 
                'project' => (string)$model->project_id,
                'seller'=>$model->seller,
                'buyer'=>$model->to_who,
                'approver'=>$model->approver,

            ]);


        } elseif ($model->status_buyer == 'Complete') {

            $model->read_unread = 1;
            $model->status_approver = 'Noted';
            $model->save();
            return $this->redirect([$model->url]);

        }  elseif ($model->status_buyer == 'Change Buyer') {


            $model->status_buyer = 'Changed';
            $model->read_unread = 1;
            $model->save();
            return $this->redirect([$model->url]);

        } 

        


    }

    public function actionIndex($id)
    {
        $user = User::find()->where(['id'=>$id])->one();

        $collection = Yii::$app->mongo->getCollection('notification');
        $model = $collection->aggregate([
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
                            'read_unread' => 1
                        ]

                    ],
                    '$or' => [
                        [
                            'status_approver' => 'Pending'
                        ],
                        [
                            'status_approver' => 'Noted'
                        ],


                    ]
                    
                ]
            ],
   

        ]);


        return $this->render('index',[

            'model' => $model
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