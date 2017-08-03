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
use app\models\LookupLeadTime;
use app\models\LookupLeadTimeSearch;
use app\models\LookupTerm;
use app\models\LookupTermSearch;

class SettingController extends Controller
{


    public function actionIndex()
    {

        $searchModelLeadTime = new LookupLeadTimeSearch();
        $dataProviderLeadTime = $searchModelLeadTime->search(Yii::$app->request->queryParams);


        $searchModelTerm = new LookupTermSearch();
        $dataProviderTerm = $searchModelTerm->search(Yii::$app->request->queryParams);



        return $this->render('index', [

            'dataProviderLeadTime' => $dataProviderLeadTime,
            'dataProviderTerm' => $dataProviderTerm,
        ]);

       

    }







}