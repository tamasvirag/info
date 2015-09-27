<?php

namespace app\components;

class Banknote
{
    public function __construct()
    {
        $this->notes = [20000,10000,5000,2000,1000,500,200,100,50,20,10,5];
    }

    public function change( $summa )
    {
        $ret = [];
        $summa = round( $summa / 5, 0 ) * 5;
        while ($summa >= 5) {
            foreach ( $this->notes as $note ) {
                if ( $summa >= $note ) {
                    $count  = floor( $summa / $note );
                    $mod    = $summa % $note;
                    $summa  = $mod;
                    $ret[$note] = $count;
                }
            }
        }
        return $ret;
    }
}
