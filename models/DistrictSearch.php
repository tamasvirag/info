<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\District;

class DistrictSearch extends District
{
    
    public function rules()
    {
        return [
            [['area_id', 'amount', 'block', 'house', 'dealer_id', 'parent_id','deleted'], 'integer'],
            [['block_price', 'house_price', 'block_price_real', 'house_price_real'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }
    
    public function search( $params = null )
    {
        $query = District::find()->orderBy( 'area_id ASC, parent_id ASC, name ASC' );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>200000],
        ]);
        
        $dataProvider->setSort(['attributes'=>[''=>'']]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if ( isset( $this->news_id ) ) {
            $news = News::findOne($this->news_id);
            if ( $news instanceof News ) {
                $district_ids = $news->newsDistrictIds;
                if ( count( $district_ids ) ) {
                    $query->where(
                        'parent_id IS NULL OR
                        ( id IN ('.implode(",", $district_ids).') AND news_id = '.$this->news_id.') OR
                        deleted = 0'  );
                }
                else {
                    $query->where(
                        'parent_id IS NULL OR
                        ( news_id = '.$this->news_id.') OR
                        deleted = 0'  );
                }
            }
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'area_id' => $this->area_id,
            'amount' => $this->amount,
            'block' => $this->block,
            'block_price' => $this->block_price,
            'house' => $this->house,
            'house_price' => $this->house_price,
            'dealer_id' => $this->dealer_id,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        
        
        if ( isset( $this->news_id ) ) {
            $query->joinWith('newsDistricts');
        }

        return $dataProvider;
    }
}
