<?php

namespace app\models;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;

use Yii;

class InvoiceGroup extends \yii\db\ActiveRecord
{
    public $created_from;
    public $created_to;

    public static function tableName()
    {
        return 'invoice_group';
    }

    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'created_from', 'created_to'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    public function search($params = null)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['created_at' => 'DESC'],
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
            'created_by' => $this->created_by,
        ]);

        if ( isset($this->created_from )) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_from)]);
        }
        if ( isset($this->created_to )) {
            $query->andFilterWhere(['<=', 'created_at', strtotime($this->created_to)]);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at'    => Yii::t('app', 'Created At'),
            'updated_at'    => Yii::t('app', 'Updated At'),
            'created_by'    => Yii::t('app', 'Invoicing user'),
            'updated_by'    => Yii::t('app', 'Updated By'),
            'created_from'  => Yii::t('app', 'Invoice Date'),

            'invoices'      => Yii::t('app', 'Invoices'),
        ];
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getCreatedByLabel()
    {
        if ( isset( $this->createdBy ) ) {
            return $this->createdBy->full_name;
        }
        else {
            return "";
        }
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getInvoiceGroupItems()
    {
        return $this->hasMany(InvoiceGroupItem::className(), ['invoice_group_id' => 'id']);
    }

    public function getInvoiceIds() {
        $invoiceIds = [];
        $groupItems = $this->invoiceGroupItems;
        if (count($groupItems)) {
            foreach($groupItems as $groupItem) {
                $invoiceIds[] = $groupItem->invoice_id;
            }
        }
        return $invoiceIds;
    }

    public function getInvoicesLinks() {
        $invoiceIds = $this->invoiceIds;
        $ret = "";
        if ( count($invoiceIds) ) {
            foreach( $invoiceIds as $invoiceId ) {
                $invoice = Invoice::findOne($invoiceId);
                $ret .= HTML::a( $invoice->invoice_number, Url::to(['invoice/update','id'=>$invoice->id])  )." - ".$invoice->clientLabel."<br>";
            }
            return $ret;
        }
        else {
            return null;
        }
    }
}
