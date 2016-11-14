<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

class InvoiceSearch extends Invoice
{
    public $invoice_date_from;
    public $invoice_date_to;
    public $invoice_deadline_date_from;
    public $invoice_deadline_date_to;
    public $storno_invoice_date_from;
    public $storno_invoice_date_to;
    public $settle_date_from;
    public $settle_date_to;
    public $created_from;
    public $created_to;

    public function rules()
    {
        return [
            [['id', 'copy_count', 'payment_method_id', 'office_id', 'client_id', 'created_by', 'updated_by'], 'integer'],
            [['invoice_date', 'invoice_date_from', 'invoice_date_to',
                'invoice_data', 'storno_invoice_data',
                'storno_invoice_date', 'storno_invoice_date_from', 'storno_invoice_date_to',
                'invoice_deadline_date', 'invoice_deadline_date_from', 'invoice_deadline_date_to',
                'settle_date', 'settle_date_from', 'settle_date_to',
                'invoice_number', 'storno_invoice_number',
                'created_at', 'created_from', 'created_to',
                'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params = null)
    {
        $query = Invoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => 'invoice_date DESC',
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if ($params) $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'copy_count' => $this->copy_count,
            'payment_method_id' => $this->payment_method_id,
            'created_by' => $this->created_by,
            'client_id' => $this->client_id,
            'office_id' => $this->office_id,
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'invoice_number', $this->invoice_number],
            ['like', 'storno_invoice_number', $this->invoice_number],
        ]);

        $query->andFilterWhere(['>=', 'invoice_date', $this->invoice_date_from])
            ->andFilterWhere(['<=', 'invoice_date', $this->invoice_date_to]);
        $query->andFilterWhere(['>=', 'invoice_deadline_date', $this->invoice_deadline_date_from])
            ->andFilterWhere(['<=', 'invoice_deadline_date', $this->invoice_deadline_date_to]);
        $query->andFilterWhere(['>=', 'storno_invoice_date', $this->storno_invoice_date_from])
            ->andFilterWhere(['<=', 'storno_invoice_date', $this->storno_invoice_date_to]);
        $query->andFilterWhere(['>=', 'settle_date', $this->settle_date_from])
            ->andFilterWhere(['<=', 'settle_date', $this->settle_date_to]);
        $query->andFilterWhere(['>=', 'created_at', $this->created_from])
            ->andFilterWhere(['<=', 'created_at', $this->created_to]);

        if ($this->client_id) {
            $query->innerJoinWith('client')->onCondition('client.id = '.$this->client_id);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return array_merge([
            'distribution_date_from'    => Yii::t('app', 'Distribution Date'),
            'distribution_date_to'      => Yii::t('app', 'Distribution Date to'),
            'invoice_date_from'         => Yii::t('app', 'Invoice Date'),
            'invoice_date_to'           => Yii::t('app', 'Invoice Date to'),
            'invoice_deadline_date_from'=> Yii::t('app', 'Invoice Deadline Date'),
            'invoice_deadline_date_to'  => Yii::t('app', 'Invoice Deadline Date to'),
            'storno_invoice_date_from'  => Yii::t('app', 'Storno Invoice Date'),
            'storno_invoice_date_to'    => Yii::t('app', 'Storno Invoice Date to'),
            'settle_date_from'          => Yii::t('app', 'Settle Date'),
            'settle_date_to'            => Yii::t('app', 'Settle Date to'),
            'created_from'              => Yii::t('app', 'Created'),
            'created_to'                => Yii::t('app', 'Created to'),
            'user_id'                   => Yii::t('app', 'User ID'),
            'client_id'                 => Yii::t('app', 'Client ID'),
        ],parent::attributeLabels());
    }
}
