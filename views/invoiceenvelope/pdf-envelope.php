<?php
use yii\helpers\Html;
$clientCount = count($dataArray);
$i = 0;

foreach($dataArray as $client_id => $client):
    $i++;
?>

<table class="table">
    <tbody>
        <tr>
            <td width="50%">
                <p>Feladó</p>
                <p>Hírös Modul Kft.</p>
                <p style="font-family: DejaVuSans;">Kőhíd u. 17.<br>Kecskemét<br>6000</p>
            </td>
            <td width="50%"></td>
        </tr>
    </tbody>
</table>
<br><br><br><br>
<table class="table">
    <tbody>
        <tr>
            <td width="60%"></td>
            <td width="40%">
            
            
                <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <p>
                                <?=$client->name?><br>
                                <?=$client->address?><br>
                                <?=$client->city?><br><br>
                                <?=$client->pcode?><br>
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