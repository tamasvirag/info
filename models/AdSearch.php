<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ad;

/**
 * AdSearch represents the model behind the search form about `app\models\Ad`.
 */
class AdSearch extends Ad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'office_id', 'client_id', 'category_id', 'highlight_type', 'business', 'ad_type', 'words', 'letters', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description', 'motto', 'image'], 'safe'],
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
        $query = Ad::find();

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
            'office_id' => $this->office_id,
            'client_id' => $this->client_id,
            'category_id' => $this->category_id,
            'highlight_type' => $this->highlight_type,
            'business' => $this->business,
            'ad_type' => $this->ad_type,
            'words' => $this->words,
            'letters' => $this->letters,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'motto', $this->motto])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
