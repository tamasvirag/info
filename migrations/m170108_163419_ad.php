<?php

use yii\db\Migration;
use yii\db\Schema;

class m170108_163419_ad extends Migration
{
    public function up()
    {
        $this->createTable( 'ad', [
            'id' => 'pk',
            'office_id'         => Schema::TYPE_INTEGER,    // megjelenés Cegléd, Kecskemét
            'user_id'           => Schema::TYPE_INTEGER,    // Üzletkötő
            'client_id'         => Schema::TYPE_INTEGER,    // Partner
            'category_id'       => Schema::TYPE_INTEGER,    // Rovat kategória
            'description'       => Schema::TYPE_TEXT,       // Hirdetés szövege
            'highlight_type_id' => Schema::TYPE_INTEGER,    // Kiemelés típusa: vastag, piros keret, sárga háttér, foto, inverz
            'motto'             => Schema::TYPE_TEXT,       // Jelige
            'business'          => Schema::TYPE_INTEGER,    // Közület: minden, ami nem magán akkor 2x annyiba kerül, keresztnél érkezőnél van használva
                                                            // helyi ar nem kell, a közület kell

            'ad_type_id'        => Schema::TYPE_INTEGER,    // Hirdetes tipusa: apro, keretes kereszt
            'words'             => Schema::TYPE_INTEGER,    // szavak, Szó
            'letters'           => Schema::TYPE_INTEGER,    // karakterek, Kar.

            'discount'          => Schema::TYPE_INTEGER,
            'price'             => Schema::TYPE_INTEGER,

            'publish_date'      => Schema::TYPE_STRING,
            'status_id'         => Schema::TYPE_INTEGER,
            'invoice_date'      => Schema::TYPE_DATE,
            'settle_date'       => Schema::TYPE_DATE,

            'created_at'        => Schema::TYPE_INTEGER,
            'updated_at'        => Schema::TYPE_INTEGER,
            'created_by'        => Schema::TYPE_INTEGER,
            'updated_by'        => Schema::TYPE_INTEGER,
        ]);

        $this->createTable( 'category', [
            'id'                => 'pk',
            'name'              => Schema::TYPE_STRING,
        ]);

        $this->addForeignKey( 'fk_ad_user_id', 'ad', 'user_id', 'user', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_client_id', 'ad', 'client_id', 'client', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_office_id', 'ad', 'office_id', 'office', 'id', 'SET NULL', 'SET NULL' );
        $this->addForeignKey( 'fk_ad_category_id', 'ad', 'category_id', 'category', 'id', 'SET NULL', 'SET NULL' );

        $this->addColumn( 'client', 'currency', Schema::TYPE_STRING );          // mértékegység
        $this->addColumn( 'client', 'discount', Schema::TYPE_INTEGER );         // kedvezmény
        $this->addColumn( 'client', 'payment_period', Schema::TYPE_INTEGER );   // fizetési határidő
        $this->addColumn( 'client', 'balance', Schema::TYPE_INTEGER );          // egyenleg, manual
        $this->addColumn( 'client', 'business', Schema::TYPE_INTEGER );         // üzleti, teljes form - különben lakossági, automatikus manual aprónál felvitelnél, csak név, cím

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

        $this->insert('category',['name'=>'ingatlan']);
        $this->insert('category',['name'=>'ingatlan vétel']);
        $this->insert('category',['name'=>'kiadó']);
        $this->insert('category',['name'=>'értékpapír - pénz']);
        $this->insert('category',['name'=>'adás-vétel']);
        $this->insert('category',['name'=>'vegyes']);
        $this->insert('category',['name'=>'jármű']);
        $this->insert('category',['name'=>'szolgáltatás']);
        $this->insert('category',['name'=>'állat']);
        $this->insert('category',['name'=>'állást kínál']);
        $this->insert('category',['name'=>'állást keres']);
        $this->insert('category',['name'=>'oktatás']);
        $this->insert('category',['name'=>'társkeresés']);
        $this->insert('category',['name'=>'üdülés']);
        $this->insert('category',['name'=>'elveszett - keresem']);
        $this->insert('category',['name'=>'gép - szerszám']);
        $this->insert('category',['name'=>'köszönetek']);

        $this->insert('ad_type',['name'=>'Apró']);
        $this->insert('ad_type',['name'=>'Keretes']);
        $this->insert('ad_type',['name'=>'Kereszt']);

        $this->insert('highlight_type',['name'=>'vastagon szedve +20%']);
        $this->insert('highlight_type',['name'=>'piros keret +500 Ft']);
        $this->insert('highlight_type',['name'=>'sárga háttér +500 Ft']);
        $this->insert('highlight_type',['name'=>'inverz +500 Ft']);
        $this->insert('highlight_type',['name'=>'fotós +3350 Ft']);

    }

    public function down()
    {
        $this->dropForeignKey( 'fk_ad_user_id', 'ad' );
        $this->dropForeignKey( 'fk_ad_client_id', 'ad' );
        $this->dropForeignKey( 'fk_ad_office_id', 'ad' );
        $this->dropForeignKey( 'fk_ad_category_id', 'ad' );
        $this->dropForeignKey( 'fk_ad_ad_type_id', 'ad' );
        $this->dropForeignKey( 'fk_ad_highlight_type_id', 'ad' );
        $this->dropTable('ad');
        $this->dropTable('category');
        $this->dropTable('ad_type');
        $this->dropTable('highlight_type');

        $this->dropColumn( 'client', 'currency' );         // mértékegység
        $this->dropColumn( 'client', 'discount' );         // kedvezmény
        $this->dropColumn( 'client', 'payment_period' );   // fizetési határidő
        $this->dropColumn( 'client', 'balance' );          // egyenleg, manual
        $this->dropColumn( 'client', 'business' );         // lakossági, automatikus manual aprónál felvitelnél, csak név, cím
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
