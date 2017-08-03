<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LookupTerm;

/**
 * LookupTermSearch represents the model behind the search form about `app\models\LookupTerm`.
 */
class LookupTermSearch extends LookupTerm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enter_by', 'update_by'], 'integer'],
            [['term', 'date_create', 'date_update'], 'safe'],
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
        $query = LookupTerm::find();

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
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
            'enter_by' => $this->enter_by,
            'update_by' => $this->update_by,
        ]);

        $query->andFilterWhere(['like', 'term', $this->term]);

        return $dataProvider;
    }
}
