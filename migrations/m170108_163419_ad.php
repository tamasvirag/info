<?php

use yii\db\Migration;
use yii\db\Schema;

class m170108_163419_ad extends Migration
{
    public function up()
    {
        $this->createTable( 'ad', [
            'id' => 'pk',
            'office_id'         => Schema::TYPE_INTEGER,    // Cegléd, Kecskemét
            'client_id'         => Schema::TYPE_INTEGER,    // Partner
            'category_id'       => Schema::TYPE_INTEGER,    // Rovat kategória
            'description'       => Schema::TYPE_TEXT,       // Hirdetés szövege
            'highlight_type_id'    => Schema::TYPE_INTEGER,    // Kiemelés típusa: vastag, piros keret, sárga háttér
            'motto'             => Schema::TYPE_TEXT,       // Jelige
            'business'          => Schema::TYPE_INTEGER,    // Közület: minden, ami nem magán akkor 2x annyiba kerül, keresztnél érkezőnél van használva
                                                            // helyi ar nem kell, a közület kell

            'ad_type_id'           => Schema::TYPE_INTEGER,    // Hirdetes tipusa: apro, keretes kereszt
            'words'             => Schema::TYPE_INTEGER,    // szavak, Szó
            'letters'           => Schema::TYPE_INTEGER,    // karakterek, Kar.
            'image'             => Schema::TYPE_STRING,

            'created_at'        => Schema::TYPE_INTEGER,
            'updated_at'        => Schema::TYPE_INTEGER,
            'created_by'        => Schema::TYPE_INTEGER,
            'updated_by'        => Schema::TYPE_INTEGER,
        ]);

        $this->createTable( 'category', [
            'id'                => 'pk',
            'name'              => Schema::TYPE_STRING,
        ]);

        $this->addForeignKey( 'fk_ad_client_id', 'ad', 'client_id', 'client', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_office_id', 'ad', 'office_id', 'office', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_category_id', 'ad', 'category_id', 'category', 'id', 'SET NULL', 'SET NULL' );

        $this->addColumn( 'client', 'currency', Schema::TYPE_STRING );          // mértékegység
        $this->addColumn( 'client', 'discount', Schema::TYPE_INTEGER );         // kedvezmény
        $this->addColumn( 'client', 'payment_period', Schema::TYPE_INTEGER );   // fizetési határidő
        $this->addColumn( 'client', 'balance', Schema::TYPE_INTEGER );          // egyenleg, manual
        $this->addColumn( 'client', 'personal', Schema::TYPE_INTEGER );         // lakossági, automatikus manual aprónál felvitelnél, csak név, cím

        $this->createTable( 'ad_type', [
            'id'                => 'pk',
            'name'              => Schema::TYPE_STRING,
        ]);

        $this->createTable( 'highlight_type', [
            'id'                => 'pk',
            'name'              => Schema::TYPE_STRING,
        ]);

        $this->addForeignKey( 'fk_ad_ad_type_id', 'ad', 'ad_type_id', 'ad_type', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_highlight_type_id', 'ad', 'highlight_type_id', 'highlight_type', 'id', 'SET NULL', 'SET NULL' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_ad_client_id' );
        $this->dropForeignKey( 'fk_ad_office_id' );
        $this->dropForeignKey( 'fk_ad_category_id' );
        $this->dropForeignKey( 'fk_ad_ad_type_id' );
        $this->dropForeignKey( 'fk_ad_highlight_type_id' );
        $this->dropTable('ad');
        $this->dropTable('category');
        $this->dropTable('ad_type');
        $this->dropTable('highlight_type');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
