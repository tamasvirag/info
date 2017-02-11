<?php

namespace app\models;

use Yii;

class Client extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'client';
    }

    public function rules()
    {
        return [
            [['name'],'required'],
            [['user_id', 'payment_method_id', 'payment_deadline', 'discount', 'payment_period', 'balance', 'business'], 'integer'],
            [['name', 'pcode', 'city', 'address', 'post_address', 'web', 'regnumber', 'taxnumber', 'contact_name', 'contact_phone', 'post_pcode', 'post_city', 'currency'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'pcode' => Yii::t('app', 'Pcode'),
            'city' => Yii::t('app', 'City'),
            'address' => Yii::t('app', 'Address'),
            'post_pcode' => Yii::t('app', 'Post Pcode'),
            'post_city' => Yii::t('app', 'Post City'),
            'post_address' => Yii::t('app', 'Post Address'),
            'web' => Yii::t('app', 'Web'),
            'regnumber' => Yii::t('app', 'Regnumber'),
            'taxnumber' => Yii::t('app', 'Taxnumber'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'user_id' => Yii::t('app', 'User ID'),
            'payment_method_id' => Yii::t('app', 'Payment Method'),
            'payment_deadline' => Yii::t('app', 'Payment Deadline'),
            'currency' => Yii::t('app', 'Currency'),
            'discount' => Yii::t('app', 'Discount'),
            'payment_period' => Yii::t('app', 'Payment Period'),
            'balance' => Yii::t('app', 'Balance'),
            'business' => Yii::t('app', 'Business Client'),
        ];
    }

    public function getNameWithAddress()
    {
        return $this->name." - ".$this->pcode." ".$this->city." ".$this->address;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserLabel()
    {
        if ( isset( $this->user ) ) {
            $active = $this->user->active?"":" ".\Yii::t('app', '(inactive)');
            return $this->user->name.$active;
        }
        else {
            return "";
        }
    }

    public function getNews()
    {
        return $this->hasMany(News::className(), ['client_id' => 'id']);
    }

    public function getAds()
    {
        return $this->hasMany(Ad::className(), ['client_id' => 'id']);
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

    public function getClientCompanies()
    {
        return $this->hasMany(ClientCompany::className(), ['client_id' => 'id']);
    }

    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['client_id' => 'id']);
    }

}
