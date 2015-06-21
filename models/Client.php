<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $name
 * @property string $pcode
 * @property string $city
 * @property string $address
 * @property string $post_address
 * @property string $web
 * @property string $regnumber
 * @property string $taxnumber
 * @property string $company_name
 * @property string $company_pcode
 * @property string $company_city
 * @property string $company_address
 * @property string $contact_name
 * @property string $contact_phone
 * @property integer $user_id
 *
 * @property User $user
 * @property News[] $news
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['user_id'], 'integer'],
            [['name', 'pcode', 'city', 'address', 'post_address', 'web', 'regnumber', 'taxnumber', 'company_name', 'company_pcode', 'company_city', 'company_address', 'contact_name', 'contact_phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
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
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
    
    public function getUserLabel()
    {
        if ( isset( $this->user ) ) {
            return $this->user->name;
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
    
}
