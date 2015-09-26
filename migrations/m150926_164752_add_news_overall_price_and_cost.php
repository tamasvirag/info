<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_164752_add_news_overall_price_and_cost extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'overall_price', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->addColumn( 'news', 'overall_cost', SCHEMA::TYPE_DECIMAL.'(8,1)' );
    }

    public function down()
    {
        $this->dropColumn( 'news', 'overall_price' );
        $this->dropColumn( 'news', 'overall_cost' );
    }
}
