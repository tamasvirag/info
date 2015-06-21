<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;

class NewsSearch extends News
{
    public $distribution_date_from;
    public $distribution_date_to;
    public $invoice_date_from;
    public $invoice_date_to;
    public $settle_date_from;
    public $settle_date_to;
    public $dealer_id;
    public $district_id;
    public $created_from;
    public $created_to;
    
    public function rules()
    {
        return [
            [['id', 'payment_method_id', 'client_id', 'user_id', 'status_id', 'dealer_id', 'district_id'], 'integer'],
            [['name', 'description',
                'distribution_date', 'distribution_date_from', 'distribution_date_to',
                'invoice_date', 'invoice_date_from', 'invoice_date_to',
                'settle_date', 'settle_date_from', 'settle_date_to', 'created_at', 'updated_at', 'created_from', 'created_to'
                ], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params = null)
    {
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($params) $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'payment_method_id' => $this->payment_method_id,
            'distribution_date' => $this->distribution_date,
            'status_id' => $this->status_id,
            'invoice_date' => $this->invoice_date,
            'settle_date' => $this->settle_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['>=', 'distribution_date', $this->distribution_date_from])
            ->andFilterWhere(['<=', 'distribution_date', $this->distribution_date_to]);
        $query->andFilterWhere(['>=', 'invoice_date', $this->invoice_date_from])
            ->andFilterWhere(['<=', 'invoice_date', $this->invoice_date_to]);
        $query->andFilterWhere(['>=', 'settle_date', $this->settle_date_from])
            ->andFilterWhere(['<=', 'settle_date', $this->settle_date_to]);
        
        $query->andFilterWhere(['>=', 'created_at', $this->created_from])
            ->andFilterWhere(['<=', 'created_at', $this->created_to]);
        
        if ($this->district_id) {
            $query->innerJoinWith('newsDistricts')->onCondition('news_district.district_id = '.$this->district_id);
        }
        
        if ($this->dealer_id) {
            $query
                ->innerJoinWith('newsDistricts')
                ->onCondition('news_district.district_id IN (SELECT id FROM district WHERE dealer_id = '.$this->dealer_id.')');
        }

        return $dataProvider;
    }
    
    public function attributeLabels()
    {
        return array_merge([
            'distribution_date_from'=> Yii::t('app', 'Distribution Date'),
            'distribution_date_to'  => Yii::t('app', 'Distribution Date to'),
            'invoice_date_from'     => Yii::t('app', 'Invoice Date'),
            'invoice_date_to'       => Yii::t('app', 'Invoice Date to'),
            'settle_date_from'      => Yii::t('app', 'Settle Date'),
            'settle_date_to'        => Yii::t('app', 'Settle Date to'),
            'dealer_id'             => Yii::t('app', 'Dealer ID'),
            'district_id'           => Yii::t('app', 'District ID'),
        ],parent::attributeLabels());
    }
}
