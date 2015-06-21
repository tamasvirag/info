<?php

use yii\db\Schema;
use yii\db\Migration;

class m150222_213839_alter_news_district extends Migration
{
    public function up()
    {
        $this->addColumn( 'news_district', 'amount', Schema::TYPE_INTEGER );
        $this->addColumn( 'news_district', 'block', Schema::TYPE_INTEGER );
        $this->addColumn( 'news_district', 'block_price', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->addColumn( 'news_district', 'house', Schema::TYPE_INTEGER );
        $this->addColumn( 'news_district', 'house_price', SCHEMA::TYPE_DECIMAL.'(8,1)' );
    }

    public function down()
    {
        $this->dropColumn( 'news_district', 'amount' );
        $this->dropColumn( 'news_district', 'block' );
        $this->dropColumn( 'news_district', 'block_price' );
        $this->dropColumn( 'news_district', 'house' );
        $this->dropColumn( 'news_district', 'house_price' );
    }
}
