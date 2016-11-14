<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

  <?php
    $name = strftime('szamla_export_%m%d%Y.xml');
    header('Content-Disposition: attachment;filename=' . $name);
    header('Content-Type: text/xml');

    $invoices = $dataProvider->getModels();

    $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
    $xml .= "<szamlak>";

    for ($i = 0; $i < count($invoices); $i++) {

      $xml .= "<szamla><invoice_number>".($invoices[$i]["invoice_number"])."</invoice_number>".
      "<invoice_date>".($invoices[$i]["invoice_date"])."</invoice_date>".
      "<invoice_deadline_date>".($invoices[$i]["invoice_deadline_date"])."</invoice_deadline_date>".
      "<payment_method_id>".($invoices[$i]["payment_method_id"])."</payment_method_id>".
      "<client_name>".(unserialize($invoices[$i]["invoice_data"])["client"]["name"])."</client_name>".
      "<price_summa>".($invoices[$i]["price_summa"])."</price_summa>".
      "<tax_summa>".($invoices[$i]["tax_summa"])."</tax_summa>".
      "<price_summa>".($invoices[$i]["price_summa"])."</price_summa>".
      "<all_summa>".($invoices[$i]["all_summa"])."</all_summa>".
      "<storno_invoice_number>".($invoices[$i]["storno_invoice_number"])."</storno_invoice_number>".
      "<city>".(unserialize($invoices[$i]["invoice_data"])["client"]["city"])."</city></szamla>";
    }

    $xml .= "</szamlak>";

    print $xml;


  ?>
