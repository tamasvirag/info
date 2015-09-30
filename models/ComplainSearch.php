<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Complain;

/**
 * ComplainSearch represents the model behind the search form about `app\models\Complain`.
 */
class ComplainSearch extends Complain
{
    public $created_at_from;
    public $created_at_to;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'district_id', 'dealer_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'address', 'phone', 'description', 'investigation_date', 'created_at_from', 'created_at_to', 'result'], 'safe'],
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
        $query = Complain::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'district_id' => $this->district_id,
            'dealer_id' => $this->dealer_id,
            'investigation_date' => $this->investigation_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        
        if ( isset( $this->created_at_from ) ) {
            $query->andFilterWhere(['>=', 'created_at', strtotime( $this->created_at_from )]);
        }
        if ( isset( $this->created_at_to ) ) {
            $query->andFilterWhere(['<=', 'created_at', strtotime( $this->created_at_to )]);
        }
//        $query->andFilterWhere(['>=', 'created_at', strtotime( $this->created_at_from )])
//           ->andFilterWhere(['<=', 'created_at', strtotime( $this->created_at_to )]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'result', $this->result]);

        return $dataProvider;
    }
}
