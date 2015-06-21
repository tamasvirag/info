<?php

use yii\db\Schema;
use yii\db\Migration;

class m150118_222825_base_tables extends Migration
{
    public function up()
    {
        $this->createTable( 'client', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
            'pcode' => Schema::TYPE_STRING,
            'city' => Schema::TYPE_STRING,
            'address' => Schema::TYPE_STRING,
            'post_address' => Schema::TYPE_STRING,
            'web' => Schema::TYPE_STRING,
            'regnumber' => Schema::TYPE_STRING,
            'taxnumber' => Schema::TYPE_STRING,
            'company_name' => Schema::TYPE_STRING,
            'company_pcode' => Schema::TYPE_STRING,
            'company_city' => Schema::TYPE_STRING,
            'company_address' => Schema::TYPE_STRING,
            'contact_name' => Schema::TYPE_STRING,
            'contact_phone' => Schema::TYPE_STRING,
            'user_id' => Schema::TYPE_INTEGER,
        ]);
        
        $this->createTable( 'area', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
        ]);
        
        $this->createTable( 'district', [
            'id' => 'pk',
            'area_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING,
            'amount' => Schema::TYPE_INTEGER,
            'block' => Schema::TYPE_INTEGER,
            'block_price' => Schema::TYPE_INTEGER,
            'house' => Schema::TYPE_INTEGER,
            'house_price' => Schema::TYPE_INTEGER,
            'dealer_id' => Schema::TYPE_INTEGER,
        ]);
        
        $this->createTable( 'news', [
            'id' => 'pk',
            'client_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_TEXT,
            'distribution_date' => Schema::TYPE_DATE,
            'status_id' => Schema::TYPE_INTEGER,
            'invoice_date' => Schema::TYPE_DATE,
            'settle_date' => Schema::TYPE_DATE,
        ]);
        
        $this->createTable( 'status', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
        ]);
        
        $this->execute("
INSERT INTO status (name) VALUE ('Új');
INSERT INTO status (name) VALUE ('Számlázott');
INSERT INTO status (name) VALUE ('Kiegyenlített');
");
        
        $this->createTable( 'news_district', [
            'news_id' => Schema::TYPE_INTEGER,
            'district_id' => Schema::TYPE_INTEGER,
        ]);
        
        $this->createTable( 'dealer', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
            'address' => Schema::TYPE_STRING,
            'birth' => Schema::TYPE_STRING,
            'taxnumber' => Schema::TYPE_STRING,
            'tajnumber' => Schema::TYPE_STRING,
            'phone' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING,
            'comment' => Schema::TYPE_TEXT,
            'helpers' => Schema::TYPE_TEXT,
            'payment_method' => Schema::TYPE_STRING,
            'other_cost' => Schema::TYPE_STRING,
        ]);
        
        $this->createTable( 'user', [
            'id' => 'pk',
            'username' => Schema::TYPE_STRING,
            'password' => Schema::TYPE_STRING,
            'full_name' => Schema::TYPE_STRING,
        ]);
        
        
        $this->addForeignKey( 'fk_client_user_id', 'client', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_district_dealer_id', 'district', 'dealer_id', 'dealer', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_district_area_id', 'district', 'area_id', 'area', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_news_client_id', 'news', 'client_id', 'client', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_news_status_id', 'news', 'status_id', 'status', 'id', 'SET NULL', 'CASCADE' );
        
        $this->addForeignKey( 'fk_news_district_news_id', 'news_district', 'news_id', 'news', 'id', 'CASCADE', 'CASCADE' );
        $this->addForeignKey( 'fk_news_district_district_id', 'news_district', 'district_id', 'district', 'id', 'CASCADE', 'CASCADE' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_client_user_id', 'client' );
        $this->dropForeignKey( 'fk_district_dealer_id', 'district' );
        $this->dropForeignKey( 'fk_district_area_id', 'district' );
        $this->dropForeignKey( 'fk_news_client_id', 'district' );
        $this->dropForeignKey( 'fk_news_status_id', 'district' );
        
        $this->dropForeignKey( 'fk_news_district_news_id', 'news_district' );
        $this->dropForeignKey( 'fk_news_district_district_id', 'news_district' );
        
        $this->dropTable( 'client' );
        $this->dropTable( 'area' );
        $this->dropTable( 'district' );
        $this->dropTable( 'news' );
        $this->dropTable( 'news_district' );
        $this->dropTable( 'dealer' );
        $this->dropTable( 'status' );
        $this->dropTable( 'user' );
    }
}
