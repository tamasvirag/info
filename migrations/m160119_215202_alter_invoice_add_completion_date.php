<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_215202_alter_invoice_add_completion_date extends Migration
{
    public function up()
    {
        $this->addColumn( 'invoice','completion_date',Schema::TYPE_DATE );
    }

    public function down()
    {
        $this->dropColumn( 'invoice','completion_date' );
    }

}
