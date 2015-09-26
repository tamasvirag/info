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
            [['user_id', 'payment_method_id'], 'integer'],
            [['name', 'pcode', 'city', 'address', 'post_address', 'web', 'regnumber', 'taxnumber', 'company_name', 'company_pcode', 'company_city', 'company_address', 'contact_name', 'contact_phone', 'company_phone'], 'string', 'max' => 255]
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
            'post_address' => Yii::t('app', 'Post Address'),
            'web' => Yii::t('app', 'Web'),
            'regnumber' => Yii::t('app', 'Regnumber'),
            'taxnumber' => Yii::t('app', 'Taxnumber'),
            'company_name' => Yii::t('app', 'Company Name'),
            'company_pcode' => Yii::t('app', 'Company Pcode'),
            'company_city' => Yii::t('app', 'Company City'),
            'company_address' => Yii::t('app', 'Company Address'),
            'company_phone' => Yii::t('app', 'Company Phone'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'user_id' => Yii::t('app', 'User ID'),
            'payment_method_id' => Yii::t('app', 'Payment Method'),
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['client_id' => 'id']);
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
    
}
