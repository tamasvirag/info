<?php

namespace app\models;

use Yii;

class NewsDistrict extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'news_district';
    }

    public function rules()
    {
        return [
            [['news_id', 'district_id', 'amount', 'block', 'house'], 'integer'],
            [['block_price', 'house_price','block_price_real', 'house_price_real'], 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'news_id' => Yii::t('app', 'News ID'),
            'district_id' => Yii::t('app', 'District ID'),
            'amount' => Yii::t('app', 'Amount'),
            'block' => Yii::t('app', 'Block'),
            'block_price' => Yii::t('app', 'Block Price'),
            'block_price_real' => Yii::t('app', 'Block Price Real'),
            'house' => Yii::t('app', 'House'),
            'house_price' => Yii::t('app', 'House Price'),
            'house_price_real' => Yii::t('app', 'House Price Real'),
        ];
    }

    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }

    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}
