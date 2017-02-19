<?php
require '/Header/header.php';
require '/Portal/top_control.php';
require 'db_exchanger.php';

$dbex = new BD_Exchange();



//function quick_string($str){
//    $arr = str_split($str);
//    $sql = '';
//    $swing = '';
//    $append = false;
//    foreach($arr as $c){
//        switch($c){
//            case '+':
//                if($append)
//                    $append=false;
//                else
//                    $append=true;
//                break;
//            //case '+':
//            default:
//                break;
//        }
//        if($append){
//            $swing .= $c;
//        } else {
//            $sql .= BD_Exchange::EXT[$c];
//        }
//        echo $c;
//    }
//    return $sql;
//}

echo "<br><br><br><h1>FUCK YOU</h1><br>";
echo "\"\"";

echo $dbex->quick_string("it+table_name+");

require '/Header/footer.php';
?>