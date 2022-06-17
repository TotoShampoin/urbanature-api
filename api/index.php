<?php
include "./call_sql.php";

$sql = "SELECT 
    `id`, 
    `taille.h`, 
    `taille.p`, 
    `nom`, 
    `info.genre`, 
    `info.espece`, 
    `info.variete` 
    `info.stade_developpement`, 
    $GEOPOSITION, 
    `meta.arrondissement`
FROM `urbanature`.`arbres` LIMIT 5";
$data = call_sql($sql);

if(!empty($data)) {
    $res = [];
    foreach($data as $key => $value) {
        $res[$key] = nest_array($value);
    }
    header('Content-Type: application/json');
    echo json_encode($res);
} else {
    echo "No data";
}


?>