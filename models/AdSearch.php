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
            [['id', 'office_id', 'user_id', 'client_id', 'category_id', 'highlight_type_id', 'business', 'ad_type_id', 'words', 'letters', 'discount', 'net_price', 'gross_price', 'vat_price', 'vat', 'status_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description', 'motto', 'publish_date', 'invoice_date', 'settle_date'], 'safe'],
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
            'sort'=> [
                'defaultOrder' => [
                    'publish_date' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 6,
            ],
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
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'category_id' => $this->category_id,
            'highlight_type' => $this->highlight_type_id,
            'business' => $this->business,
            'ad_type' => $this->ad_type_id,
            'words' => $this->words,
            'letters' => $this->letters,
            'discount' => $this->discount,
            'net_price' => $this->net_price,
            'gross_price' => $this->gross_price,
            'vat_price' => $this->vat_price,
            'vat' => $this->vat,
            'status_id' => $this->status_id,
            'invoice_date' => $this->invoice_date,
            'settle_date' => $this->settle_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'motto', $this->motto])
            ->andFilterWhere(['like', 'publish_date', $this->publish_date]);

        return $dataProvider;
    }
}
