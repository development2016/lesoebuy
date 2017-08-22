<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ItemOffline;

/**
 * ItemOfflineSearch represents the model behind the search form about `app\models\ItemOffline`.
 */
class ItemOfflineSearch extends ItemOffline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'item_code', 'item_name', 'brand', 'model', 'specification', 'lead_time', 'validity', 'cost', 'quantity', 'cit', 'shipping', 'remark', 'date_create', 'enter_by', 'update_by', 'date_update'], 'safe'],
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
        $query = ItemOffline::find();

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
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'item_code', $this->item_code])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'specification', $this->specification])
            ->andFilterWhere(['like', 'lead_time', $this->lead_time])
            ->andFilterWhere(['like', 'validity', $this->validity])
            ->andFilterWhere(['like', 'cost', $this->cost])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'cit', $this->cit])
            ->andFilterWhere(['like', 'shipping', $this->shipping])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'date_create', $this->date_create])
            ->andFilterWhere(['like', 'enter_by', $this->enter_by])
            ->andFilterWhere(['like', 'update_by', $this->update_by])
            ->andFilterWhere(['like', 'date_update', $this->date_update]);

        return $dataProvider;
    }
}
