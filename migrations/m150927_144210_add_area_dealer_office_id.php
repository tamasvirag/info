<?php

use yii\db\Schema;
use yii\db\Migration;

class m150927_144210_add_area_dealer_office_id extends Migration
{
    public function up()
    {
        $this->addColumn( 'area', 'office_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_area_office_id', 'area', 'office_id', 'office', 'id', 'SET NULL', 'SET NULL' );
        $this->addColumn( 'dealer', 'office_id', Schema::TYPE_INTEGER );
        $this->addForeignKey( 'fk_dealer_office_id', 'dealer', 'office_id', 'office', 'id', 'SET NULL', 'SET NULL' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_area_office_id' );
        $this->dropForeignKey( 'fk_dealer_office_id' );
    }
}
