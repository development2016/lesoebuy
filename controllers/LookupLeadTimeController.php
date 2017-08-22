<?php

namespace app\controllers;

use Yii;
use app\models\LookupLeadTime;
use app\models\LookupLeadTimeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LookupLeadTimeController implements the CRUD actions for LookupLeadTime model.
 */
class LookupLeadTimeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    /**
     * Creates a new LookupLeadTime model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LookupLeadTime();

        if ($model->load(Yii::$app->request->post())  ) {

            $model->enter_by= Yii::$app->user->identity->id;
            $model->date_create =date('Y-m-d H:i:s');
            $model->save();

            Yii::$app->session->setFlash('success', 'New <b>Lead Time</b> Successful Saved');
            return $this->redirect(['setting/index']);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LookupLeadTime model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->update_by= Yii::$app->user->identity->id;
            $model->date_update =date('Y-m-d H:i:s');
            $model->save();


            Yii::$app->session->setFlash('success', '<b>'.$model->lead_time.'</b> Successful Updated');
            return $this->redirect(['setting/index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LookupLeadTime model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', '<b>Lead Time</b> Successful Delete');
        return $this->redirect(['setting/index']);

    }

    /**
     * Finds the LookupLeadTime model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LookupLeadTime the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LookupLeadTime::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
