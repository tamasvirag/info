<?php

use yii\db\Schema;
use yii\db\Migration;

class m150214_172755_payment extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'payment_method_id', Schema::TYPE_INTEGER );
        $this->createTable( 'payment_method', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
        ]);
        $this->addForeignKey( 'fk_news_payment_method_id', 'news', 'payment_method_id', 'payment_method', 'id', 'SET NULL', 'CASCADE' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_news_payment_method_id', 'news' );
        $this->dropTable( 'payment_method' );
        $this->dropColumn( 'news', 'payment_method_id' );
        
    }
}
