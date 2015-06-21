<?php

use yii\db\Schema;
use yii\db\Migration;

class m150228_205550_alter_news_district_dealer extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'user_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_news_user_id', 'news', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE' );
        
        $this->addColumn( 'user', 'active', Schema::TYPE_BOOLEAN );
        
        $this->addColumn( 'district', 'block_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->addColumn( 'district', 'house_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->addColumn( 'news_district', 'block_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->addColumn( 'news_district', 'house_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
    }

    public function down()
    {

        $this->dropColumn( 'news', 'user_id' );
        $this->dropForeignKey( 'fk_news_user_id', 'news' );
        
        $this->dropColumn( 'user', 'active' );
        
        $this->dropColumn( 'district', 'block_price_real' );
        $this->dropColumn( 'district', 'house_price_real' );
        $this->dropColumn( 'news_district', 'block_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->dropColumn( 'news_district', 'house_price_real', SCHEMA::TYPE_DECIMAL.'(8,1)' );
    }
}
