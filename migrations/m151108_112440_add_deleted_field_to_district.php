<?php

use yii\db\Schema;
use yii\db\Migration;

class m151108_112440_add_deleted_field_to_district extends Migration
{
    public function up()
    {
        $this->addColumn( 'district', 'deleted', Schema::TYPE_BOOLEAN.' DEFAULT 0' );
    }

    public function down()
    {
        $this->dropColumn( 'district', 'deleted' );
    }

}
