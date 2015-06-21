<?php

use yii\db\Schema;
use yii\db\Migration;

class m150222_101602_alter_district_parent extends Migration
{
    public function up()
    {
        $this->addColumn( 'district', 'parent_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_district_parent_id', 'district', 'parent_id', 'district', 'id', 'SET NULL', 'CASCADE' );
    }

    public function down()
    {
        $this->dropColumn();
        $this->addForeignKey( 'fk_district_parent_id' );
    }
}
