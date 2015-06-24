<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property string $invoice_number
 * @property string $invoice_date
 * @property string $invoice_deadline_date
 * @property string $invoice_data
 * @property string $storno_invoice_number
 * @property string $storno_invoice_date
 * @property string $storno_invoice_data
 * @property integer $copy_count
 * @property string $settle_date
 * @property integer $payment_method_id
 * @property integer $office_id
 *
 * @property Office $office
 * @property PaymentMethod $paymentMethod
 * @property User $user
 * @property InvoiceItem[] $invoiceItems
 */
class Invoice extends \yii\db\ActiveRecord
{
    const TYPE_CASH     = 1;
    const TYPE_TRANSFER = 2;
    const TYPE_STORNO   = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_date', 'invoice_data', 'storno_invoice_data'], 'string'],
            [['invoice_deadline_date', 'settle_date', 'created_at', 'updated_at'], 'safe'],
            [['copy_count', 'payment_method_id', 'office_id', 'client_id', 'price_summa', 'tax_summa', 'all_summa', 'created_by', 'updated_by'], 'integer'],
            [['invoice_number', 'storno_invoice_number', 'storno_invoice_date'], 'string', 'max' => 255]
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoice_number' => Yii::t('app', 'Invoice Number'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'invoice_deadline_date' => Yii::t('app', 'Invoice Deadline Date'),
            'invoice_data' => Yii::t('app', 'Invoice Data'),
            'storno_invoice_number' => Yii::t('app', 'Storno Invoice Number'),
            'storno_invoice_date' => Yii::t('app', 'Storno Invoice Date'),
            'storno_invoice_data' => Yii::t('app', 'Storno Invoice Data'),
            'copy_count' => Yii::t('app', 'Copy Count'),
            'settle_date' => Yii::t('app', 'Settle Date'),
            'payment_method_id' => Yii::t('app', 'Payment Method ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'office_id' => Yii::t('app', 'Office ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'price_summa' => Yii::t('app', 'Price'),
            'tax_summa' => Yii::t('app', 'Tax'),
            'all_summa' => Yii::t('app', 'All'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
        ];
    }


    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }
    
    public function getOfficeLabel()
    {
        if ( isset( $this->office ) ) {
            return $this->office->name;
        }
        else {
            return "";
        }
    }


    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }
    
    public function getPaymentMethodLabel()
    {
        if ( isset( $this->paymentMethod ) ) {
            return $this->paymentMethod->name;
        }
        else {
            return "";
        }
    }


    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
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
    
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    public function getUpdatedByLabel()
    {
        if ( isset( $this->updatedBy ) ) {
            return $this->updatedBy->full_name;
        }
        else {
            return "";
        }
    }
    
    
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
    
    public function getClientLabel()
    {
        if ( isset( $this->client ) ) {
            return $this->client->name;
        }
        else {
            return "";
        }
    }


    public function getInvoiceItems()
    {
        return $this->hasMany(InvoiceItem::className(), ['invoice_id' => 'id']);
    }
    
    public function getNextInvoiceNumber($type) {
        if ( $type == self::TYPE_CASH) {
            $seq = 'seq_news_invoice_number_cash';
            $pre = 'TK'.date('Y');
        }
        elseif ( $type == self::TYPE_TRANSFER) {
            $seq = 'seq_news_invoice_number_transfer';
            $pre = 'TA'.date('Y');
        }
        elseif ( $type == self::TYPE_STORNO) {
            $seq = 'seq_news_invoice_number_storno';
            $pre = 'TS'.date('Y');
        }
        else {
            die('Invalid type');
        }
        
        $connection = \Yii::$app->db;
        $result = $connection->createCommand("SELECT nextval('".$seq."') as invoice_number")->queryAll();
        return $pre.$result[0]['invoice_number'];
    }
}
