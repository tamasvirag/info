<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use Yii;

/**
 * This is the model class for table "ad".
 *
 * @property integer $id
 * @property integer $office_id
 * @property integer $user_id
 * @property integer $client_id
 * @property integer $category_id
 * @property string $description
 * @property integer $highlight_type
 * @property string $motto
 * @property integer $business
 * @property integer $ad_type
 * @property integer $words
 * @property integer $letters
 * @property integer $discount
 * @property integer $price
 * @property string $publish_date
 * @property integer $status_id
 * @property string $invoice_date
 * @property string $settle_date
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property AdType $adType
 * @property Category $category
 * @property Client $client
 * @property HighlightType $highlightType
 * @property Office $office
 * @property User $user
 */
class Ad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id', 'user_id', 'client_id', 'category_id', 'highlight_type_id', 'business', 'ad_type_id', 'words', 'letters', 'discount', 'price', 'status_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description', 'motto'], 'string'],
            [['invoice_date', 'settle_date'], 'safe'],
            [['publish_date'], 'string', 'max' => 255],
            [['ad_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdType::className(), 'targetAttribute' => ['ad_type_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['highlight_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => HighlightType::className(), 'targetAttribute' => ['highlight_type_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'office_id' => Yii::t('app', 'Publishing Office'),
            'user_id' => Yii::t('app', 'User'),
            'client_id' => Yii::t('app', 'Client'),
            'category_id' => Yii::t('app', 'Category'),
            'description' => Yii::t('app', 'Description'),
            'highlight_type_id' => Yii::t('app', 'Highlight Type'),
            'motto' => Yii::t('app', 'Motto'),
            'business' => Yii::t('app', 'Business'),
            'ad_type_id' => Yii::t('app', 'Ad Type'),
            'words' => Yii::t('app', 'Words'),
            'letters' => Yii::t('app', 'Letters'),
            'discount' => Yii::t('app', 'Discount'),
            'price' => Yii::t('app', 'Price'),
            'publish_date' => Yii::t('app', 'Publish Date'),
            'status_id' => Yii::t('app', 'Status ID'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'settle_date' => Yii::t('app', 'Settle Date'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
        ];
    }


    public function getAdType()
    {
        return $this->hasOne(AdType::className(), ['id' => 'ad_type_id']);
    }
    public function getAdTypeLabel()
    {
        if ( isset( $this->adType ) ) {
            return $this->adType->name;
        }
        else {
            return "";
        }
    }

    public function getHighlightType()
    {
        return $this->hasOne(HighlightType::className(), ['id' => 'ad_type_id']);
    }
    public function getHighlightTypeLabel()
    {
        if ( isset( $this->hightlightType ) ) {
            return $this->hightlightType->name;
        }
        else {
            return "";
        }
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryLabel()
    {
        if ( isset( $this->category ) ) {
            return $this->category->name;
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
}
