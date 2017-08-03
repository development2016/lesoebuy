<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LookupLeadTime;

/**
 * LookupLeadTimeSearch represents the model behind the search form about `app\models\LookupLeadTime`.
 */
class LookupLeadTimeSearch extends LookupLeadTime
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enter_by', 'update_by'], 'integer'],
            [['lead_time', 'date_create', 'date_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LookupLeadTime::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'enter_by' => $this->enter_by,
            'update_by' => $this->update_by,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'lead_time', $this->lead_time]);

        return $dataProvider;
    }
}
