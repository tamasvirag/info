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
    
    public function getPaymentByDistributionDate( $date_from = null, $date_to = null ) {
        
        $where = "";
        if (isset( $date_from ) && date('Y-m-d',  strtotime($date_from)) == $date_from ) {
            $where .= ' AND news.distribution_date >= "'.$date_from.'"';
        }
        if (isset( $date_to ) && date('Y-m-d',  strtotime($date_to)) == $date_to ) {
            $where .= ' AND news.distribution_date <= "'.$date_to.'"';
        }
        
        $sql = \Yii::$app->db->createCommand( '
            SELECT news.id, 
        	news.`name` AS news_name, 
        	news_district.district_id, 
        	news_district.news_id, 
        	news_district.amount AS news_district_amount, 
        	news_district.block AS news_district_block, 
        	news_district.block_price AS news_district_block_price, 
        	news_district.house AS news_district_house, 
        	news_district.house_price AS news_district_house_price, 
        	news_district.block_price_real AS news_district_block_price_real, 
        	news_district.house_price_real AS news_district_house_price_real, 
        	district.id AS district_id, 
        	district.area_id, 
        	district.`name` AS district_name, 
        	district.amount AS district_amount, 
        	district.block AS district_block, 
        	district.block_price AS district_block_price, 
        	district.house AS district_house, 
        	district.house_price AS district_house_price, 
        	district.block_price_real AS district_block_price_real, 
        	district.house_price_real AS district_house_price_real, 
        	district.dealer_id, 
        	dealer.id AS dealer_id, 
        	dealer.`name` AS dealer_name,
        	news.distribution_date,
        	district.parent_id
        FROM news_district INNER JOIN news ON news_district.news_id = news.id
        	 INNER JOIN district ON district.id = news_district.district_id
        	 INNER JOIN dealer ON dealer.id = district.dealer_id
        WHERE dealer.id =:dealer_id' .$where.
        ' ORDER BY news.distribution_date ASC, news.id ASC' );
        
        $sql->bindValue( ':dealer_id', $this->id );
        
        $result = $sql->queryAll();
        
        $summa = 0;
        $rows = [];
        foreach( $result as $row ) {   
            if ($row['parent_id'] != null) {
                $block = $row['news_district_block']!==null?floatval($row['news_district_block']):floatval($row['district_block']);
                $block_price_real = $row['news_district_block_price_real']!==null?floatval($row['news_district_block_price_real']):floatval($row['district_block_price_real']);
                
                $house = $row['news_district_house']!==null?floatval($row['news_district_house']):floatval($row['district_house']);
                $house_price_real = $row['news_district_house_price_real']!==null?floatval($row['news_district_house_price_real']):floatval($row['district_house_price_real']);
                
                $summa += $block * $block_price_real;
                $summa += $house * $house_price_real;
                
                $rows[] = [
                    'news_name'         => $row['news_name'],
                    'distribution_date' => $row['distribution_date'],
                    'district_name'     => $row['district_name'],
                    
                    'block'             => $block,
                    'block_price_real'  => $block_price_real,
                    'block_all'         => $block * $block_price_real,
                    
                    'house'             => $house,
                    'house_price_real'  => $house_price_real,
                    'house_all'         => $house * $house_price_real,
                ];
            }        
        }
        $data['summa'] = $summa;
        $data['rows'] = $rows;
        return $data;
    }
}





















