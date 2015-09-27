<?php

use yii\db\Schema;
use yii\db\Migration;

class m150927_132332_add_client_address_fields extends Migration
{
    public function up()
    {
        $this->addColumn( 'client', 'post_pcode', Schema::TYPE_STRING );
        $this->addColumn( 'client', 'post_city', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn( 'client', 'post_pcode' );
        $this->dropColumn( 'client', 'post_city' );
    }
}
