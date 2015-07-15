<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_190321_add_payment_method_id_to_client extends Migration
{
    public function up()
    {
        $this->addColumn( 'client', 'payment_method_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_client_payment_method_id', 'client', 'payment_method_id', 'payment_method', 'id', 'SET NULL', 'CASCADE' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_client_payment_method_id', 'client' );
        $this->dropTable( 'payment_method' );
        $this->dropColumn( 'client', 'payment_method_id' );
        
    }
    
}
