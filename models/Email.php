<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $text
 * @property string $url
 * @property string $date_create
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['date_create'], 'safe'],
            [['from', 'to', 'subject', 'url','from_who','to_who','project_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'subject' => 'Subject',
            'text' => 'Text',
            'url' => 'Url',
            'date_create' => 'Date Create',
            'from_who' => 'From Who',
            'to_who' => 'To Who',
            'project_no' => 'Project No'
        ];
    }
}
