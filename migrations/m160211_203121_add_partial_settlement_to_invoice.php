<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_203121_add_partial_settlement_to_invoice extends Migration
{
    public function up()
    {
        $this->addColumn( 'invoice', 'partial_settlement', SCHEMA::TYPE_INTEGER );
    }

    public function down()
    {
        $this->dropColumn( 'invoice', 'partial_settlement' );
    }

}
