<?php

use yii\db\Schema;
use yii\db\Migration;

class m150930_201744_create_table_complain extends Migration
{
    public function up()
    {
        $this->createTable( 'complain', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
            'address' => Schema::TYPE_STRING,
            'phone' => Schema::TYPE_STRING,
            'district_id' => Schema::TYPE_INTEGER,
            'dealer_id' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_STRING,
            'investigation_date' => Schema::TYPE_DATE,
            'result' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
        ]);
        
        $this->addForeignKey( 'fk_complain_district_id', 'complain', 'district_id', 'district', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_complain_dealer_id', 'complain', 'dealer_id', 'dealer', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_complain_created_by', 'complain', 'created_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_complain_updated_by', 'complain', 'updated_by', 'user', 'id', 'SET NULL', 'SET NULL' );
        
        $this->addColumn( 'dealer', 'active', Schema::TYPE_BOOLEAN );
    }
    
    public function down()
    {
        $this->dropTable('complain');
        $this->dropForeignKey( 'fk_complain_district_id' );
        $this->dropForeignKey( 'fk_complain_dealer_id' );
        $this->dropForeignKey( 'fk_complain_created_by' );
        $this->dropForeignKey( 'fk_complain_updated_by' );
        $this->dropColumn( 'dealer', 'active' );
    }
    
}
