<?php

namespace app\commands;

use yii\console\Controller;
use app\models\District;

class DistrictImportController extends Controller
{
    public function actionIndex()
    {
        $areas = [
            "Cegléd" => 1,
            "Dél-Pest vidék" => 2,
            "Kecskemét" => 3,
            "Kecskemét vidék" => 4
        ];
        
        $handle = fopen( 'commands/district.tsv', 'r');

        if ($handle) {
            while( ($line = fgets($handle)) != FALSE) {
                $line = explode("\t", $line);
                foreach( $line as $key => $val ) {
                    $line[$key] = trim($val);
                }
                
                $district = new District;
                $district->area_id = $areas[ $line[0] ];
                $district->name = $line[1];
                $district->amount = $line[2];
                $district->block = $line[3];
                $district->block_price = $line[4]+0;
                $district->house = $line[5];
                $district->house_price = $line[6]+0;
                if( !$district->save() ) {
                    var_dump( $district->errors );
                }
            }        
        }
        fclose($handle);      
    }
}
