<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_company".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $company_name
 * @property string $company_pcode
 * @property string $company_city
 * @property string $company_address
 * @property string $company_phone
 *
 * @property Client $client
 */
class ClientCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id'], 'integer'],
            [['company_name', 'company_pcode', 'company_city', 'company_address', 'company_phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'company_name' => Yii::t('app', 'Company Name'),
            'company_pcode' => Yii::t('app', 'Company Pcode'),
            'company_city' => Yii::t('app', 'Company City'),
            'company_address' => Yii::t('app', 'Company Address'),
            'company_phone' => Yii::t('app', 'Company Phone'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }
}
