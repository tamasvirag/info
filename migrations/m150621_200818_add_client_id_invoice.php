<?php

use yii\db\Schema;
use yii\db\Migration;

class m150621_200818_add_client_id_invoice extends Migration
{
    public function up()
    {
        $this->addColumn( 'invoice', 'client_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_invoice_client_id', 'invoice', 'client_id', 'client', 'id', 'SET NULL', 'SET NULL' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_invoice_client_id' );
    }
}
