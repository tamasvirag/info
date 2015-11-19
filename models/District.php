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
            [['area_id', 'name', 'block', 'house'],'required'],
            [['area_id', 'amount', 'block', 'house', 'dealer_id', 'parent_id','deleted'], 'integer'],
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
            'amount' => Yii::t('app', 'Amount original'),
            'block' => Yii::t('app', 'Block'),
            'block_price' => Yii::t('app', 'Block Price'),
            'block_price_real' => Yii::t('app', 'Block Price real'),
            'house' => Yii::t('app', 'House'),
            'house_price' => Yii::t('app', 'House Price'),
            'house_price_real' => Yii::t('app', 'House Price real'),
            'dealer_id' => Yii::t('app', 'Dealer ID'),
            'parent_id' => Yii::t('app', 'District Parent ID'),
            'deleted' => Yii::t('app', 'status'),
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
    
    public function getDeletedLabel()
    {
        if ( $this->deleted ) {
            return \Yii::t('app','deleted');
        }
        else {
            return \Yii::t('app','active');
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
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->amount = $this->block + $this->house;
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ( $this->parent_id !== null && $this->parent_id != "" ) {
            $children = self::find()->where('parent_id = '.$this->parent_id)->all();
            $block = 0;
            $house = 0;
            $parent = $this->parent;
            if ( count($children) ) {
                foreach( $children as $child ) {
                    $block += $child->block;
                    $house += $child->house;
                }
            }
            $parent->block = $block;
            $parent->house = $house;
            $parent->amount = $block + $house;
            $parent->save();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}
