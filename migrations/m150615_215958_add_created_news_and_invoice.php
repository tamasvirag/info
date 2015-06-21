<?php

use yii\db\Schema;
use yii\db\Migration;

class m150615_215958_add_created_news_and_invoice extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'created_at', Schema::TYPE_INTEGER );
        $this->addColumn( 'news', 'updated_at', Schema::TYPE_INTEGER );
        $this->addColumn( 'invoice', 'created_at', Schema::TYPE_INTEGER );
        $this->addColumn( 'invoice', 'updated_at', Schema::TYPE_INTEGER );
    }

    public function down()
    {
        $this->dropColumn( 'news', 'created_at' );
        $this->dropColumn( 'invoice', 'created_at' );
        $this->dropColumn( 'news', 'updated_at' );
        $this->dropColumn( 'invoice', 'updated_at' );
    }

}
