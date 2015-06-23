<?php

namespace app\models;

use Yii;
use app\models\News;

/**
 * This is the model class for table "invoice_item".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $item_id
 * @property string $item_class
 *
 * @property Invoice $invoice
 */
class InvoiceItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'item_id'], 'integer'],
            [['item_class'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_id' => Yii::t('app', 'Invoice ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'item_class' => Yii::t('app', 'Item Class'),
        ];
    }

    public function getModel()
    {
        if ( isset($this->id) && isset($this->item_class) ) {
            return call_user_func_array(__NAMESPACE__."\\".$this->item_class."::findOne", [$this->item_id] );
        }
        else {
            return null;
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
}
