<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_203500_add_client_company_table extends Migration
{
    public function up()
    {
        $this->createTable( 'client_company', [
            'id'            => 'pk',
            'client_id'     => Schema::TYPE_INTEGER,
            'company_name'  => Schema::TYPE_STRING,
            'company_pcode' => Schema::TYPE_STRING,
            'company_city'  => Schema::TYPE_STRING,
            'company_address' => Schema::TYPE_STRING,
            'company_phone' => Schema::TYPE_STRING,
            ]
        );
        $this->dropColumn( 'client', 'company_name' );
        $this->dropColumn( 'client', 'company_pcode' );
        $this->dropColumn( 'client', 'company_city' );
        $this->dropColumn( 'client', 'company_address' );
        $this->dropColumn( 'client', 'company_phone' );
        $this->addForeignKey( 'fk_client_company_client_id', 'client_company', 'client_id', 'client', 'id', 'CASCADE', 'CASCADE' );
    }

    public function down()
    {
        $this->dropForeignKey( 'fk_client_company_client_id' );
        $this->dropTable( 'client_company' );
    }

}
