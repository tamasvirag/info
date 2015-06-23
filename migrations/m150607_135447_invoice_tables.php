<?php

use yii\db\Schema;
use yii\db\Migration;

class m150607_135447_invoice_tables extends Migration
{
    public function up()
    {
        
        $this->execute('
CREATE TABLE `sequence_data` (
    `sequence_name` varchar(100) NOT NULL,
    `sequence_increment` int(11) unsigned NOT NULL DEFAULT 1,
    `sequence_min_value` int(11) unsigned NOT NULL DEFAULT 1,
    `sequence_max_value` bigint(20) unsigned NOT NULL DEFAULT 18446744073709551615,
    `sequence_cur_value` bigint(20) unsigned DEFAULT 1,
    `sequence_cycle` boolean NOT NULL DEFAULT FALSE,
    PRIMARY KEY (`sequence_name`)
);');

    
        $this->execute('
CREATE FUNCTION `nextval` (`seq_name` varchar(100))
RETURNS bigint(20) NOT DETERMINISTIC
BEGIN
    DECLARE cur_val bigint(20);
 
    SELECT
        sequence_cur_value INTO cur_val
    FROM
        sequence_data
    WHERE
        sequence_name = seq_name
    ;
 
    IF cur_val IS NOT NULL THEN
        UPDATE
            sequence_data
        SET
            sequence_cur_value = IF (
                (sequence_cur_value + sequence_increment) > sequence_max_value,
                IF (
                    sequence_cycle = TRUE,
                    sequence_min_value,
                    NULL
                ),
                sequence_cur_value + sequence_increment
            )
        WHERE
            sequence_name = seq_name
        ;
    END IF;
 
    RETURN cur_val;
END;');

        $this->execute("
INSERT INTO sequence_data (sequence_name) VALUE ('seq_news_invoice_number_transfer');
INSERT INTO sequence_data (sequence_name) VALUE ('seq_news_invoice_number_cash');
INSERT INTO sequence_data (sequence_name) VALUE ('seq_news_invoice_number_storno');
");

        $this->createTable( 'office', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING,
        ]);
        
        $this->execute("
INSERT INTO office (name) VALUE ('Cegléd');
INSERT INTO office (name) VALUE ('Kecskemét');
");
        
        $this->addColumn( 'user', 'office_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_user_office_id', 'user', 'office_id', 'office', 'id', 'SET NULL', 'CASCADE' );
        
        $this->createTable( 'invoice', [
            'id' => 'pk',
            
            'invoice_number' => Schema::TYPE_STRING,
            'invoice_date' => Schema::TYPE_TEXT,
            'invoice_deadline_date' => Schema::TYPE_DATE,
            'invoice_data' => Schema::TYPE_TEXT,
            
            'storno_invoice_number' => Schema::TYPE_STRING,
            'storno_invoice_date' => Schema::TYPE_DATE,
            'storno_invoice_data' => Schema::TYPE_TEXT,
            
            'copy_count' => Schema::TYPE_INTEGER.' DEFAULT 0',
            'printed' => Schema::TYPE_INTEGER,
            
            'settle_date' => Schema::TYPE_DATE,
            'payment_method_id' => Schema::TYPE_INTEGER,
            
            'user_id' => Schema::TYPE_INTEGER,
            'office_id' => Schema::TYPE_INTEGER,
        ]);
        
        $this->addForeignKey( 'fk_invoice_payment_method_id', 'invoice', 'payment_method_id', 'payment_method', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_invoice_user_id', 'invoice', 'user_id', 'user', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey( 'fk_invoice_office_id', 'invoice', 'office_id', 'office', 'id', 'SET NULL', 'CASCADE' );
        
        $this->createTable( 'invoice_item', [
            'id' => 'pk',
            'invoice_id' => Schema::TYPE_INTEGER,
            'item_id' => Schema::TYPE_INTEGER,
            'item_class' => Schema::TYPE_STRING,
        ]);
        
        $this->addForeignKey( 'fk_invoice_item_invoice_id', 'invoice_item', 'invoice_id', 'invoice', 'id', 'SET NULL', 'CASCADE' );
    }

    public function down()
    {
        
        $this->dropTable( 'sequence_data' );
        $this->execute('DROP FUNCTION `nextval`;');
        
        $this->dropForeignKey( 'fk_invoice_payment_method_id', 'invoice' );
        $this->dropForeignKey( 'fk_invoice_user_id', 'invoice' );
        $this->dropTable( 'invoice' );
        
        $this->dropForeignKey( 'fk_invoice_item_invoice_id', 'invoice_item' );
        $this->dropTable( 'invoice_item' );
        
        
        $this->dropTable( 'office' );
        
        return true;
    }    
}
