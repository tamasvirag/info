<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_155453_add_client_company_phone extends Migration
{
    public function up()
    {
        $this->addColumn( 'client', 'company_phone', Schema::TYPE_STRING );
    }

    public function down()
    {
        $this->dropColumn( 'client', 'company_phone' );
    }
}
