<?php

namespace app\models;

use Yii;

class District extends \yii\db\ActiveRecord
{
    public $news_id;
    
    public static function tableName()
    {
        return 'district';
    }

    public function rules()
    {
        return [
            [['area_id', 'amount', 'block', 'house', 'dealer_id', 'parent_id'], 'integer'],
            [['block_price', 'house_price', 'block_price_real', 'house_price_real'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'area_id' => Yii::t('app', 'Area ID'),
            'name' => Yii::t('app', 'Name'),
            'amount' => Yii::t('app', 'Amount'),
            'block' => Yii::t('app', 'Block'),
            'block_price' => Yii::t('app', 'Block Price'),
            'block_price_real' => Yii::t('app', 'Block Price real'),
            'house' => Yii::t('app', 'House'),
            'house_price' => Yii::t('app', 'House Price'),
            'house_price_real' => Yii::t('app', 'House Price real'),
            'dealer_id' => Yii::t('app', 'Dealer ID'),
            'parent_id' => Yii::t('app', 'District Parent ID'),
        ];
    }
    
    public function getParent()
    {
        return $this->hasOne(District::className(), ['id' => 'parent_id']);
    }
    
    public function getFullLabel()
    {
        if ( isset( $this->area ) ) {
            return $this->area->name.' - '.$this->name;
        }
        else {
            return $this->name;
        }
    }

    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'area_id']);
    }
    
    public function getAreaLabel()
    {
        if ( isset( $this->area ) ) {
            return $this->area->name;
        }
        else {
            return "";
        }
    }

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
    
    public function getND() {
        if (isset($this->news_id)) {
            return $this->getNewsDistricts()->onCondition('news_district.news_id = '.$this->news_id)->all();
        }
        else {
            return $this->getNewsDistricts();
        }
    }
    
    public function getNewsDistricts()
    {
        return $this->hasMany(NewsDistrict::className(), ['district_id' => 'id']);
    }
}
