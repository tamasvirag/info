<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "dealer".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $birth
 * @property string $taxnumber
 * @property string $tajnumber
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property string $helpers
 * @property string $payment_method
 * @property string $other_cost
 *
 * @property District[] $districts
 */
class Dealer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'helpers'], 'string'],
            [['name', 'address', 'birth', 'taxnumber', 'tajnumber', 'phone', 'email', 'payment_method', 'other_cost'], 'string', 'max' => 255]
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
            'birth' => Yii::t('app', 'Birth'),
            'taxnumber' => Yii::t('app', 'Taxnumber'),
            'tajnumber' => Yii::t('app', 'Tajnumber'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'comment' => Yii::t('app', 'Comment'),
            'helpers' => Yii::t('app', 'Helpers'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'other_cost' => Yii::t('app', 'Other Cost'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(District::className(), ['dealer_id' => 'id']);
    }
    
    public function getDistrictsLabel()
    {
        $districts = $this->districts;
        $ret = [];
        if ( count( $districts ) ) {
            foreach( $districts as $district ) {
                $ret[] = "<nobr>".HTML::a( HTML::encode( $district->area->name." - ".$district->name ), ['/district/update','id'=>$district->id] )."</nobr>";
            }
        }
        return implode( "<br>", $ret );
    }
}
