<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "delivery-address".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $country
 * @property mixed $state
 * @property mixed $location
 * @property mixed $warehouse_name
 * @property mixed $address
 * @property mixed $latitude
 * @property mixed $longitude
 */
class DeliveryAddress extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['procument', 'delivery-address'];
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
            'country',
            'state',
            'location',
            'warehouse_name',
            'address',
            'postcode',
            'latitude',
            'longitude',
            'contact',
            'email',
            'fax'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'state', 'location', 'warehouse_name', 'address', 'latitude', 'longitude','postcode','contact','email','fax'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'country' => 'Country',
            'state' => 'State',
            'location' => 'Location',
            'warehouse_name' => 'Warehouse Name',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'postcode' => 'Postcode',
            'contact' => 'Tel No',
            'email' => 'Email',
            'fax' => 'Fax No'



        ];
    }

    public function getCountrys()
    {
        return $this->hasOne(LookupCountry::className(), ['id' => 'country']);
    }


    public function getStates()
    {
        return $this->hasOne(LookupState::className(), ['id' => 'state']);
    }
    
}
