<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_group_item".
 *
 * @property integer $invoice_group_id
 * @property integer $invoice_id
 *
 * @property Invoice $invoice
 * @property InvoiceGroup $invoiceGroup
 */
class InvoiceGroupItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_group_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_group_id', 'invoice_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_group_id' => Yii::t('app', 'Invoice Group ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceGroup()
    {
        return $this->hasOne(InvoiceGroup::className(), ['id' => 'invoice_group_id']);
    }
}