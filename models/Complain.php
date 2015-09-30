<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "complain".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property integer $district_id
 * @property integer $dealer_id
 * @property string $description
 * @property string $investigation_date
 * @property string $result
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property Dealer $dealer
 * @property District $district
 */
class Complain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id', 'dealer_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['investigation_date'], 'safe'],
            [['name', 'address', 'phone', 'description', 'result'], 'string', 'max' => 255]
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
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'district_id' => Yii::t('app', 'District ID'),
            'dealer_id' => Yii::t('app', 'Dealer ID'),
            'description' => Yii::t('app', 'Description'),
            'investigation_date' => Yii::t('app', 'Investigation Date'),
            'result' => Yii::t('app', 'Result'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    public function getUpdatedByLabel()
    {
        if ( isset( $this->user ) ) {
            return $this->user->full_name;
        }
        else {
            return "";
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    public function getCreatedByLabel()
    {
        if ( isset( $this->user ) ) {
            return $this->user->full_name;
        }
        else {
            return "";
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealer()
    {
        return $this->hasOne(Dealer::className(), ['id' => 'dealer_id']);
    }
    public function getDealerLabel()
    {
        if ( isset( $this->dealer ) ) {
            return $this->dealer->name;
        }
        else {
            return "";
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }
    public function getDistrictLabel()
    {
        if ( isset( $this->district ) ) {
            return $this->district->name;
        }
        else {
            return "";
        }
    }
}
