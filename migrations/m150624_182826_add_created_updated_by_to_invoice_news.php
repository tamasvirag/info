<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_182826_add_created_updated_by_to_invoice_news extends Migration
{
    public function up()
    {
        $this->addColumn( 'news', 'created_by', Schema::TYPE_INTEGER );
        $this->addColumn( 'news', 'updated_by', Schema::TYPE_INTEGER );
        $this->addColumn( 'invoice', 'created_by', Schema::TYPE_INTEGER );
        $this->addColumn( 'invoice', 'updated_by', Schema::TYPE_INTEGER );
        
        $this->addForeignKey( 'fk_invoice_created_by', 'invoice', 'created_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_invoice_updated_by', 'invoice', 'updated_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_news_created_by', 'news', 'created_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_news_updated_by', 'news', 'updated_by', 'user', 'id', 'SET NULL', 'SET NULL' );
    }

    public function down()
    {
        $this->dropColumn( 'news', 'created_by' );
        $this->dropColumn( 'invoice', 'created_by' );
        $this->dropColumn( 'news', 'updated_by' );
        $this->dropColumn( 'invoice', 'updated_by' );
        
        $this->addForeignKey( 'fk_invoice_created_by' );
        $this->addForeignKey( 'fk_invoice_updated_by' );
        $this->addForeignKey( 'fk_news_created_by' );
        $this->addForeignKey( 'fk_news_updated_by' );
    }
}
