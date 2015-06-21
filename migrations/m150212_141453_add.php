<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_141453_add extends Migration
{
    public function up()
    {
        $this->addColumn( 'user', 'auth_key', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn( 'user', 'auth_key' );
    }
}
