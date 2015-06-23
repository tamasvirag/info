<?php

use yii\db\Schema;
use yii\db\Migration;

class m150622_194411_add_price_tax_to_invoice extends Migration
{
    public function up()
    {
        $this->addColumn('invoice','price_summa',Schema::TYPE_INTEGER);
        $this->addColumn('invoice','tax_summa',Schema::TYPE_INTEGER);
        $this->addColumn('invoice','all_summa',Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('invoice','price_summa');
        $this->dropColumn('invoice','tax_summa');
        $this->dropColumn('invoice','all_summa');
    }

}
