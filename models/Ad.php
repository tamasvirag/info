<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ad".
 *
 * @property integer $id
 * @property integer $office_id
 * @property integer $client_id
 * @property integer $category_id
 * @property string $description
 * @property integer $highlight_type
 * @property string $motto
 * @property integer $business
 * @property integer $ad_type
 * @property integer $words
 * @property integer $letters
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Category $category
 * @property Client $client
 * @property Office $office
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
            [['office_id', 'client_id', 'category_id', 'highlight_type', 'business', 'ad_type', 'words', 'letters', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description', 'motto'], 'string'],
            [['image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::className(), 'targetAttribute' => ['office_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_id' => 'Office ID',
            'client_id' => 'Client ID',
            'category_id' => 'Category ID',
            'description' => 'Description',
            'highlight_type' => 'Highlight Type',
            'motto' => 'Motto',
            'business' => 'Business',
            'ad_type' => 'Ad Type',
            'words' => 'Words',
            'letters' => 'Letters',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }
}
