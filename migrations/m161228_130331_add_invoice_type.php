<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\InvoiceSearch;
use app\models\Invoice;

class m161228_130331_add_invoice_type extends Migration
{
    public function up()
    {
        $this->addColumn( 'invoice', 'invoice_type', SCHEMA::TYPE_INTEGER );

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search();
        $dataProvider->setPagination(false);
        $invoices = $dataProvider->getModels();
        foreach ($invoices as $invoice ) {
            $invoice->invoice_type = Invoice::INVOICE_TYPE_NORMAL;
            $invoice->save();
            if ($invoice->storno_invoice_date != '') {
                $new_invoice = new Invoice;
                $new_invoice->invoice_type = Invoice::INVOICE_TYPE_STORNO;
                $new_invoice->invoice_number = $invoice->storno_invoice_number;
                $new_invoice->storno_invoice_number = $invoice->invoice_number;
                $new_invoice->invoice_date = $invoice->invoice_date;
                $new_invoice->invoice_deadline_date = $invoice->invoice_deadline_date;
                $new_invoice->settle_date = $invoice->settle_date;
                $new_invoice->completion_date = $invoice->completion_date;
                $new_invoice->invoice_data = $invoice->storno_invoice_data;
                $new_invoice->payment_method_id = $invoice->payment_method_id;
                $new_invoice->office_id = $invoice->office_id;
                $new_invoice->client_id = $invoice->client_id;
                $new_invoice->price_summa = $invoice->price_summa * (-1);
                $new_invoice->tax_summa = $invoice->tax_summa * (-1);
                $new_invoice->all_summa = $invoice->all_summa * (-1);
                $new_invoice->save();
            }
        }

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(
            "
            UPDATE invoice t1
            JOIN invoice t2 ON t2.storno_invoice_number = t1.invoice_number
            SET
            t1.created_at = UNIX_TIMESTAMP( CONCAT( t2.storno_invoice_date, ' 19:00:00' ) ),
            t1.updated_at = UNIX_TIMESTAMP( CONCAT( t2.storno_invoice_date, ' 19:00:00' ) )
            WHERE t1.invoice_type = 2;

            UPDATE invoice t1
            JOIN invoice t2 ON t2.storno_invoice_number = t1.invoice_number
            SET
            t1.created_by = t2.created_by,
            t1.updated_by = t2.updated_by
            WHERE t1.invoice_type = 2;
            "
            );

        $result = $command->execute();
    }

    public function down()
    {
        $this->dropColumn( 'invoice', 'invoice_type' );
    }
}
