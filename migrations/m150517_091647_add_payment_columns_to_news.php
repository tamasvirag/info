<?php

use yii\db\Schema;
use yii\db\Migration;

class m150517_091647_add_payment_columns_to_news extends Migration
{
    public function up()
    {
//        $this->addColumn('news','payment_deadline_date',Schema::TYPE_DATE);
//        $this->addColumn('news','invoice_number',Schema::TYPE_STRING);
//        $this->addColumn('news','invoice_data',Schema::TYPE_TEXT);
    }

    public function down()
    {
//        $this->dropColumn('news','payment_deadline_date');
//        $this->dropColumn('news','invoice_number');
//        $this->dropColumn('news','invoice_data');
    } 
}
