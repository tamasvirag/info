<?php

use yii\db\Schema;
use yii\db\Migration;

class m150218_215723_alter_district extends Migration
{
    public function up()
    {
        $this->alterColumn( 'district', 'block_price', SCHEMA::TYPE_DECIMAL.'(8,1)' );
        $this->alterColumn( 'district', 'house_price', SCHEMA::TYPE_DECIMAL.'(8,1)' );
    }

    public function down()
    {
        $this->alterColumn( 'district', 'block_price', SCHEMA::TYPE_INTEGER );
        $this->alterColumn( 'district', 'house_price', SCHEMA::TYPE_INTEGER );
    }
}
