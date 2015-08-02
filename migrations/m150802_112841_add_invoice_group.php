<?php

use yii\db\Schema;
use yii\db\Migration;

class m150802_112841_add_invoice_group extends Migration
{
    public function up()
    {
        $this->createTable( 'invoice_group', [
            'id' => 'pk',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
        ]);
        $this->addForeignKey( 'fk_invoice_group_created_by', 'invoice_group', 'created_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_invoice_group_updated_by', 'invoice_group', 'updated_by', 'user', 'id', 'SET NULL', 'SET NULL' );
                
        $this->createTable( 'invoice_group_item', [
            'invoice_group_id' => Schema::TYPE_INTEGER,
            'invoice_id' => Schema::TYPE_INTEGER,
        ]);
        $this->addForeignKey( 'fk_news_invoice_group_item_invoice_group_id', 'invoice_group_item', 'invoice_group_id', 'invoice_group', 'id', 'CASCADE', 'CASCADE' );
        $this->addForeignKey( 'fk_news_invoice_group_item_inovice_id', 'invoice_group_item', 'invoice_id', 'invoice', 'id', 'CASCADE', 'CASCADE' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_invoice_group_created_by', 'invoice_group' );
        $this->dropForeignKey( 'fk_invoice_group_updated_by', 'invoice_group' );
        $this->dropTable( 'invoice_group' );
        
        $this->dropForeignKey( 'fk_news_invoice_group_item_invoice_group_id', 'invoice_group_item' );
        $this->dropForeignKey( 'fk_news_invoice_group_item_inovice_id', 'invoice_group_item' );
        $this->dropTable( 'invoice_group_item' );
        
        return true;
    }
    
}
