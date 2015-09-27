<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dealer;

class DealerSearch extends Dealer
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'address', 'birth', 'taxnumber', 'tajnumber', 'phone', 'email', 'comment', 'helpers', 'payment_method', 'other_cost', 'office_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Dealer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'name'=>SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'office_id' => $this->office_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'birth', $this->birth])
            ->andFilterWhere(['like', 'taxnumber', $this->taxnumber])
            ->andFilterWhere(['like', 'tajnumber', $this->tajnumber])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'helpers', $this->helpers])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'other_cost', $this->other_cost]);

        return $dataProvider;
    }
}
