<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$name = strftime('szamla_export_%m%d%Y.xml');
header('Content-Disposition: attachment;filename='.$name);
header('Content-Type: text/xml');

$invoices = $dataProvider->getModels();

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$xml .= "
<szamlak>
<export_datuma>".date("Y-m-d")."</export_datuma>
  <export_szla_db>".count($invoices)."</export_szla_db>
  <kezdo_ido>".$searchModel->invoice_date_from."</kezdo_ido>
  <zaro_ido>".$searchModel->invoice_date_to."</zaro_ido>
  <kezdo_szla_szam></kezdo_szla_szam>
  <zaro_szla_szam></zaro_szla_szam>";

foreach ($invoices as $invoice) {
  $dataSet = unserialize($invoice->invoice_data);

  $xml .= "
  <szamla>
    <fejlec>
      <szlasorszam>".$invoice["invoice_number"]."</szlasorszam>
      <szlatipus>"."</szlatipus>
      <szladatum>".$invoice["invoice_date"]."</szladatum>
      <teljdatum>".$invoice["settle_date"]."</teljdatum>
    </fejlec>

    <szamlakibocsato>
      <adoszam>".\Yii::$app->params['company']['taxNumber']."</adoszam>
      <nev>".\Yii::$app->params['company']['name']."</nev>
      <cim>
        <iranyitoszam>".\Yii::$app->params['company']['postcode']."</iranyitoszam>
        <telepules>".\Yii::$app->params['company']['city']."</telepules>
        <kozterulet_neve>".\Yii::$app->params['company']['street']."</kozterulet_neve>
        <kozterulet_jellege>".\Yii::$app->params['company']['streetType']."</kozterulet_jellege>
        <hazszam>".\Yii::$app->params['company']['houseNumber']."</hazszam>
      </cim>
    </szamlakibocsato>

    <vevo>
      <adoszam>".$dataSet['client']->taxnumber."</adoszam>
      <nev>".$dataSet['client']->name."</nev>
      <cim>
        <iranyitoszam>".$dataSet['client']->pcode."</iranyitoszam>
        <telepules>".$dataSet['client']->city."</telepules>
        <kozterulet_neve>".$dataSet['client']->address."</kozterulet_neve>
      </cim>
    </vevo>";

    foreach ($dataSet['items'] as $nettoegysegar => $items) {
    $xml .=
    "<termek_szolgaltatas_tetelek>
      <termeknev>Szórólap terjesztés</termeknev>
      <menny>".$items['amount']."</menny>
      <mertekegys>db</mertekegys>
      <nettoar>".$items['price']."</nettoar>
      <nettoegysar>".$nettoegysegar."</nettoegysar>
      <adokulcs>27</adokulcs>
      <adoertek>".$items['tax']."</adoertek>
      <bruttoar>".$items['summa']."</bruttoar>
    </termek_szolgaltatas_tetelek>";
    }

/*<modosito_szla>
      <eredeti_sorszam>str1234</eredeti_sorszam>
    </modosito_szla>*/

    $xml .=
    "
    <nem_kotelezo>
      <fiz_hatarido>".$invoice->invoice_deadline_date."</fiz_hatarido>
      <fiz_mod>".$invoice->paymentMethodLabel."</fiz_mod>
      <penznem>HUF</penznem>
    </nem_kotelezo>
    <osszesites>
      <afarovat>
        <nettoar>".$dataSet['price_summa']."</nettoar>
        <adokulcs>27</adokulcs>
        <adoertek>".$dataSet['tax_summa']."</adoertek>
        <bruttoar>".$dataSet['all_summa']."</bruttoar>
      </afarovat>
      <vegosszeg>
        <nettoarossz>".$dataSet['price_summa']."</nettoarossz>
        <afaertekossz>".$dataSet['tax_summa']."</afaertekossz>
        <bruttoarossz>".$dataSet['all_summa']."</bruttoarossz>
        <afa_tartalom>".$dataSet['tax_summa']."</afa_tartalom>
      </vegosszeg>
    </osszesites>
  </szamla>

";
  }

$xml .= "</szamlak>";
print $xml;

?>
