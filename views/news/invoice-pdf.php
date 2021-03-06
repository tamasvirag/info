<?php
use yii\helpers\Html;

$items  = $data['items'];
$news   = $data['news'];
$client = $data['client'];
?>

<table class="table">
    <tbody>
        <tr>
            <td width="33%">
                <?php if ($data['type']=='normal' || $data['type']=='storno'): ?>
                    <p>A számla 2 példányban készült.<br><?=$data['copy']?>. példány
                <?php elseif($data['type']=='copy'): ?>
                    <p>1. számla másolat<br>A számla 2 példányban készült.</p>
                <?php endif; ?>
                </p>
            </td>
            <td width="34%" align="center"><h1>SZÁMLA</h1></td>
            <td width="33%"></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <tbody>
    <tr>
        <td width="50%" valign="top">
            <p class="small">Szállító neve, címe</p>
            <p class="title">Hírös Modul Kft.</p>
            <p style="font-family: DejaVuSans;">6000 Kecskemét<br>Kőhíd u. 17.</p>
            <p>Adószám: 11428046-2-03<br>
                Bank: UniCredit Bank Hunga<br>
                Bankszámla: 10918001-00000097-38110009</p>
        </td>
        <td width="50%" valign="top">
            <p class="small">Vevő neve, címe</p>
            <p class="title"><?=$client->name?></p>
            <p><?=$client->pcode?> <?=$client->city?><br><?=$client->address?></p></td>
    </tr>
    <tr></tr>
    </tbody>
</table>

<table class="table table-bordered">
    <tbody>
        <tr>
            <td width="20%" align="center">
                <p>Fizetés módja<br><?=$news->paymentMethodLabel?></p>
            </td>
            <td width="20%" align="center">
                <p>Teljesítés dátuma<br><?=$news->settle_date?></p>
            </td>
            <td width="20%" align="center">
                <p>Számla kelte<br><?=$news->invoice_date?></p>
            </td>
            <td width="20%" align="center">
                <p>Fizetési határidő<br><?=$news->invoice_deadline_date?></p>
            </td>
            <td width="20%" align="center">
                <?php if ($data['type']=='storno'): ?>
                    <p>Számlaszám<br><?=$news->storno_invoice_number?><br><br> <?=$news->invoice_number?> számú számla érvénytelenítő számlája.</p>
                <?php else: ?>
                    <p>Számlaszám<br><?=$news->invoice_number?></p>
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>

<?php if ($data['type']=='storno'): ?>
    <p align="center">ÉRVÉNYTELENÍTŐ SZÁMLA</p>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th width="8%" align="right">
                <p class="small">ITJ/SZJ:</p>
            </th>
            <th width="20%" align="left">
                <p class="small">Megnevezés:</p>
            </th>
            <th width="12%" align="right">
                <p class="small">Mennyiség:</p>
            </th>
            <th width="11%" align="right">
                <p class="small">Egységár:</p>
            </th>
            <th width="14%" align="right">
                <p class="small">Nettó összeg:</p>
            </th>
            <th width="9%" align="right">
                <p class="small">%</p>
            </th>
            <th width="12%" align="right">
                <p class="small">Áfa:</p>
            </th>
            <th width="14%" align="right">
                <p class="small">Bruttó összeg:</p>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach( $items as $unitPrice => $item ): ?>
        <tr>
            <td align="right"><p class="small">744013</p></td>
            <td align="left"><p class="small">Szórólap terjesztés</p></td>
            <td align="right"><p class="small"><?=$item['amount']?> db</p></td>
            <td align="right"><p class="small"><?=$unitPrice?> Ft</p></td>
            <td align="right"><p class="small"><?=$item['price']?> Ft</p></td>
            <td align="right"><p class="small">27%</p></td>
            <td align="right"><p class="small"><?=$item['tax']?> Ft</p></td>
            <td align="right"><p class="small"><?=$item['summa']?> Ft</p></p></td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" valign="bottom"><p>Azaz:</p></td>
            <td colspan="5" valign="bottom" align="right">
                <p class="title">Fizetendő: <?=$data['all_summa']?> Ft</p>
                <p class="small"><?=$data['all_summa_string']?> forint</p>
            </td>
        </tr>
    </tfoot>
</table>

<table>
    <tbody>
        <tr>
            <td width="55%" valign="top">
                <p class="small">
                    Tisztelt Partnerünk!<br>
                    Kérjük szíveskedjen feltüntetni, hogy milyen számlaszámra vonatkozik átutalása.<br>
                    Késedelmes fizetés esetén 20% késedelmi kamatot számítunk fel!<br>
                    Reklamáció esetén levelezési cím: 2700 Cegléd, Múzeum u. 3. tel.: 53/500-030<br>
                    Köszönjük, hogy igénybe vette cégünk szolgáltatását!<br>
                </p>
            </td>
            <td width="45%">

                <table class="table">
                    <thead>
                        <tr>
                            <th width="20%" align="right">
                                <p class="small">Áfa:</p>
                            </th>
                            <th width="25%" align="right">
                                <p class="small">Áfa alap:</p>
                            </th>
                            <th width="25%" align="right">
                                <p class="small">Áfa összeg:</p>
                            </th>
                            <th width="30%" align="right">
                                <p class="small">Bruttó összeg:</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="right"><p class="small">27%-os</p></td>
                            <td align="right"><p class="small"><?=$data['price_summa']?> Ft</p></td>
                            <td align="right"><p class="small"><?=$data['tax_summa']?> Ft</p></td>
                            <td align="right"><p class="small"><?=$data['all_summa']?> Ft</p></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align="right"><p class="small">Összesen:</p></td>
                            <td align="right"><p class="small"><?=$data['price_summa']?> Ft</p></td>
                            <td align="right"><p class="small"><?=$data['tax_summa']?> Ft</p></td>
                            <td align="right"><p class="small"><?=$data['all_summa']?> Ft</p></td>
                        </tr>
                    </tfoot>
                </table>
                
            </td>
        </tr>
    </tbody>
</table>
<br><br><br><br>
<table align="right">
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td align="left"></td>
        <td class="sign" align="center" width="30%"><p class="small">aláírás</p></td>
    </tr>
</table>
