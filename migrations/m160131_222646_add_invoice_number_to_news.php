<?php

use yii\db\Schema;
use yii\db\Migration;

class m160131_222646_add_invoice_number_to_news extends Migration
{
    public function up()
    {
        $this->addColumn('news','invoice_number',Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('news','invoice_number');
    }

}
