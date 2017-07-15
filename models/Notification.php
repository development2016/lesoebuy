<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "notification".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $status
 * @property mixed $details
 * @property mixed $date_request
 * @property mixed $project_no
 * @property mixed $project_id
 * @property mixed $request_by
 * @property mixed $to_whom
 * @property mixed $read_unread
 * @property mixed $date_create
 */
class Notification extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['procument', 'notification'];
    }

    /**
     * @return \yii\mongodb\Connection the MongoDB connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mongo');
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'status',
            'details',
            'date_request',
            'project_no',
            'project_id',
            'from_who',
            'to_who',
            'read_unread',
            'date_create',
            'url',
            'seller',
            'approver'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'details', 'date_request', 'project_no', 'project_id', 'from_who', 'to_who', 'read_unread', 'date_create','url','seller','approver'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'status' => 'Status',
            'details' => 'Details',
            'date_request' => 'Date Request',
            'project_no' => 'Project No',
            'project_id' => 'Project ID',
            'from_who' => 'from who',
            'to_who' => 'To Who',
            'read_unread' => 'Read Unread',
            'date_create' => 'Date Create',
        ];
    }

    public static function notify()
    {
            

        $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $model = Notification::find()->where(['read_unread' => 0,'to_who'=>$user->account_name])->count();  

        return $model;

    }


    public static function listnotify()
    {
            

        $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();

        $model = Notification::find()->where(['read_unread' => 0,'to_who'=>$user->account_name])->all();  

        return $model;

    }



}
