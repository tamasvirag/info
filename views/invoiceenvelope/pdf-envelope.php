<?php
use yii\helpers\Html;
$clientCount = count($dataArray);
$i = 0;

$size = '';
if( $format_ == 'LC5') {
    $size = 'big';
}

foreach($dataArray as $client_id => $client):
    $i++;
?>

<table class="table">
    <tbody>
        <tr>
            <td width="70%">
                <p class="<?=$size?>">Feladó<br>
                Hírös Modul Kft.</p>
                <p class="<?=$size?>" style="font-family: DejaVuSans;">Kőhíd u. 17.<br>Kecskemét<br>6000</p>
            </td>
            <td width="30%" class="dij-hitelezve" align="center">
                <p class="<?=$size?>">DÍJ HITELEZVE<br>2701 Cegléd</p>
            </td>
        </tr>
    </tbody>
</table>
<br><br>
<?php if( $format_ == 'LC5'): ?>
<br><br><br><br><br><br><br>
<?php endif; ?>
<table class="table">
    <tbody>
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <p class="big">
                                <?=$client->name?><br>
                                <?php if( $client->post_pcode != "" && $client->post_city != "" && $client->post_address != "" ): ?>
                                    <?=$client->post_address?><br><?=$client->post_city?><br><?=$client->post_pcode?>
                                <?php else: ?>
                                    <?=$client->address?><br><?=$client->city?><br><?=$client->pcode?>
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                </tbody>
                </table>
                
            </td>            
        </tr>
    </tbody>
</table>

<?php  if($clientCount!=$i): ?>
<pagebreak resetpagenum="1" />
<?php endif; ?>

<?php endforeach; ?>
