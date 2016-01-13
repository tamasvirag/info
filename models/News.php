<?php

namespace app\models;

use Yii;
use app\components\NumberToString;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class News extends \yii\db\ActiveRecord
{
    const STATUS_NEW        = 1;
    const STATUS_INVOICED   = 2;
    const STATUS_SETTLED    = 3;

    public $storno_invoice_number;
    
    public static function tableName()
    {
        return 'news';
    }

    public function rules()
    {
        return [
            [['name','payment_method_id', 'user_id', 'client_id'],'required'],
            [['client_id', 'status_id', 'payment_method_id' ,'user_id', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['overall_price','overall_cost','net_revenue','cost','newscount',], 'number'],
            [['distribution_date', 'invoice_date', 'settle_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_id' => Yii::t('app', 'Client ID'),
            'payment_method_id' => Yii::t('app', 'Payment Method'),
            'name' => Yii::t('app', 'News Name'),
            'description' => Yii::t('app', 'Description'),
            'distribution_date' => Yii::t('app', 'Distribution Date'),
            'status_id' => Yii::t('app', 'Status ID'),
            'invoice_date' => Yii::t('app', 'Invoice Date'),
            'settle_date' => Yii::t('app', 'Settle Date'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'newsCount' => Yii::t('app', 'News Count'),
            'overall_price' => Yii::t('app', 'Overall price'),
            'overall_cost' => Yii::t('app', 'Overall cost'),
            'newscount' => Yii::t('app', 'News Count'),
            'cost' => Yii::t('app', 'Cost'),
            'net_revenue' => Yii::t('app', 'Net revenue'),
        ];
    }
    
    public static function isNew($newsIds) {
        if ( is_array($newsIds) ) {
            foreach( $newsIds as $news_id ) {
                if ( ($news = self::findOne($news_id) ) === null) {
                    return false;
                }
                if ($news->status_id != self::STATUS_NEW) {
                    return false;
                }
            }
        }
        else {
            return false;
        }
        return true;
    }
    
    public static function getInvoiceData($newsIds, $invoice_type = 'normal') {
        
        if ( is_array($newsIds) ) {
        
            $data_set   = array();
            $items      = [];
            $clients    = []; // there must be ONE from all news
            $cost       = 0;
        
            /**
            * walkthrough the news, collect items, group same prices
            */
            foreach( $newsIds as $news_id ) {
                if ( ($news = self::findOne($news_id) ) === null) {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
                
                if ( count( $news->newsDistricts ) ) {
                    foreach ( $news->newsDistricts as $newsDistrict ) {
                        $district = $newsDistrict->district;
                        
                        // főkategóriákat nem vesszük bele
                        if ($district->parent_id != null) {
                            $block = $newsDistrict->block!==null?floatval($newsDistrict->block):floatval($district->block);
                            $block_price = $newsDistrict->block_price!==null?floatval($newsDistrict->block_price):floatval($district->block_price);
                            $house = $newsDistrict->house!==null?floatval($newsDistrict->house):floatval($district->house);
                            $house_price = $newsDistrict->house_price!==null?floatval($newsDistrict->house_price):floatval($district->house_price);
                           
                            if ( $block_price != 0 && $block != 0) {
                                if ( !isset($items[strval($block_price)]) ) {
                                    $items[strval($block_price)] = $block;
                                }
                                else {
                                    $items[strval($block_price)] += $block;
                                }
                            }
                           
                            if ( $house_price != 0 && $house != 0) {
                                if ( !isset($items[strval($house_price)]) ) {
                                    $items[strval($house_price)] = $house;
                                }
                                else {
                                    $items[strval($house_price)] += $house;
                                }
                            }
                            
                            $block_price_real = $newsDistrict->block_price_real!==null?floatval($newsDistrict->block_price_real):floatval($district->block_price_real);
                            $house_price_real = $newsDistrict->house_price_real!==null?floatval($newsDistrict->house_price_real):floatval($district->house_price_real);
                            $cost += $block * $block_price_real;
                            $cost += $house * $house_price_real;
                        }
                                                
                    } 
                }
                $clients[$news->client->id] = 1;
                $client = $news->client;
            }
            
            if ( count($clients) > 1 ) die();
        
            $base = 1;
            if ( $invoice_type == 'storno' ) {
                $base = -1;
            }
            
            $all_summa      = 0;
            $price_summa    = 0;
            $tax_summa      = 0;
                
            if ( count($items) ) {
                foreach ( $items as $unitPrice => $amount ) {
                    $items[$unitPrice] = [
                        'amount'=> $amount,
                        'price' => round($base * floatval($unitPrice) * $amount),
                        'tax'   => round($base * floatval($unitPrice) * $amount * 0.27),
                        'summa' => round($base * floatval($unitPrice) * $amount * 1.27),
                    ];
                    $price_summa    += round($base * floatval($unitPrice) * $amount);
                    $tax_summa      += round($base * floatval($unitPrice) * $amount * 0.27);
                    $all_summa      += round($base * floatval($unitPrice) * $amount * 1.27);
                }
            }
            
            $converter = new NumberToString();
            $all_summa_string = $converter->toString($all_summa);
            
            $data_set['client']             = $client;
            $data_set['items']              = $items;        
            $data_set['price_summa']        = $price_summa;
            $data_set['tax_summa']          = $tax_summa;
            $data_set['all_summa']          = $all_summa;
            $data_set['all_summa_string']   = $all_summa_string;
            $data_set['cost']               = $cost;
            
            return $data_set;
        }
        else {
            return null;
        }
    }
    
    public function getNewsCount() { // példányszám
        $all = 0;
        if ( count( $this->newsDistricts ) ) {
            foreach ( $this->newsDistricts as $newsDistrict ) {
                $district = $newsDistrict->district;
                
                // főkategóriákat nem vesszük bele
                if ($district->parent_id != null) {
                    $all += $newsDistrict->block;
                    $all += $newsDistrict->house;
                }
                
            }
        }        
        return $all;
    }
    
    public static function getLastDistributionDate($newsIds) {
        $last = null;
        if (count($newsIds)) {
            foreach($newsIds as $news_id) {
                $news = self::findOne($news_id);
                if ( $last === null ) {
                    $last = $news->distribution_date;
                }
                elseif ( $news->distribution_date > $last ) {
                    $last = $news->distribution_date;
                }
            }
        }
        return $last;
    }
    
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }
    
    public function getPaymentMethodLabel()
    {
        if ( isset( $this->paymentMethod ) ) {
            return $this->paymentMethod->name;
        }
        else {
            return "";
        }
    }
    
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }
    
    public function getStatusLabel()
    {
        if ( isset( $this->status ) ) {
            return $this->status->name;
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
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getUserLabel()
    {
        if ( isset( $this->user ) ) {
            return $this->user->full_name;
        }
        else {
            return "";
        }
    }
    
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    public function getCreatedByLabel()
    {
        if ( isset( $this->createdBy ) ) {
            return $this->createdBy->full_name;
        }
        else {
            return "";
        }
    }
    
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    public function getUpdatedByLabel()
    {
        if ( isset( $this->updatedBy ) ) {
            return $this->updatedBy->full_name;
        }
        else {
            return "";
        }
    }
    
    public function getNewsDistricts()
    {
        return $this->hasMany(NewsDistrict::className(), ['news_id' => 'id']);
    }
    
    public function getNewsDistrictIds()
    {
        $ret = [];
        $nds = $this->newsDistricts;
        if ( count( $nds ) ) {
            foreach( $nds as $nd ) {
                $ret[] = $nd->district_id;
            }
        }
        return $ret;
    }
    
    public function deleteDistricts()
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->delete( 'news_district', 'news_id = '.$this->id )->execute();
    }
    
    public function getLabel() {
        return $this->name;
    }
    
    public function updateNewscountRevenue()
    {
        $this->newscount    = $this->getNewsCount();
        $invoiceData        = self::getInvoiceData([$this->id]);
        $this->net_revenue  = $invoiceData['price_summa'];
        $this->cost         = $invoiceData['cost'];
        $this->save();
    }
}
