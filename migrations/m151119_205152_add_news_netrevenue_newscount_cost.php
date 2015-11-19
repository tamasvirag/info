<?php

use yii\db\Schema;
use yii\db\Migration;

class m151119_205152_add_news_netrevenue_newscount_cost extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'newscount', SCHEMA::TYPE_INTEGER );
        $this->addColumn( 'news', 'net_revenue', SCHEMA::TYPE_DECIMAL.'(12,1)' );
        $this->addColumn( 'news', 'cost', SCHEMA::TYPE_DECIMAL.'(12,1)' );
    }

    public function down()
    {
        $this->dropColumn( 'news', 'newscount' );
        $this->dropColumn( 'news', 'net_revenue' );
        $this->dropColumn( 'news', 'cost' );
    }

}
