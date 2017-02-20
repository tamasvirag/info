<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "highlight_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Ad[] $ads
 */
class HighlightType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'highlight_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','type'], 'string', 'max' => 255],
            [['amount'], 'integer'],
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
            'amount' => Yii::t('app', 'Amount'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ad::className(), ['highlight_type_id' => 'id']);
    }
}
