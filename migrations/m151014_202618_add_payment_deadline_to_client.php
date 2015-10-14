<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_202618_add_payment_deadline_to_client extends Migration
{
    public function up()
    {
        $this->addColumn('client','payment_deadline',Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('client','payment_deadline');
    }
}
