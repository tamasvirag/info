<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\District;

class DistrictSearch extends District
{   
    public function search( $params = null )
    {
        $query = District::find()->orderBy( 'area_id ASC, parent_id ASC, name ASC' );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>2000],
        ]);
        
        $dataProvider->setSort(['attributes'=>[''=>'']]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        
        
        if ( isset( $this->news_id ) ) {
            $query->joinWith('newsDistricts');
        }

        return $dataProvider;
    }
}
