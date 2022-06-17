<?php
include "./call_sql.php";
include "./error_msg.php";

$search = $_GET['query'] ?? '';
$table = $_GET['table'] ?? '';
$limit = $_GET['limit'] ?? 5;
$lat = $_GET['lat'] ?? '48.858370';
$lng = $_GET['lng'] ?? '2.294481';

$closest = get_closest_table($table);
if(strpos($search, ";") !== false) {
    echo_error("Invalid search query", $search, false);
    exit;
}
if($closest['distance'] != 0) {
    echo_error("Table `$table` does not exist", $table, $closest['table'] ?? false);
    exit;
}
if(!is_numeric($limit)) {
    echo_error("Limit must be a number", $limit, false);
    exit;
}
if(!is_numeric($lat)) {
    echo_error("Latitude must be a number", $lat, false);
    exit;
}
if(!is_numeric($lng)) {
    echo_error("Longitude must be a number", $lng, false);
    exit;
}

switch($table) {
    case 'arbres':
        $rows = [
            'id',
            'nom',
            'info.genre',
        ];
        break;
    case 'arbres-remarquables':
        $rows = [
            'id',
            'nom',
            'info.genre',
        ];
        break;
    case 'espaces-verts':
        $rows = [
            'id',
            'nom',
            'info.categorie',
            'info.type',
        ];
        break;
    case 'jardins-partages':
        $rows = [
            'id',
            'nom',
            'info.type_ev',
            'info.type_jardin',
        ];
        break;
    
}
$rows_select = implode(', ', array_map(function($row) {
    return "`$row`";
}, $rows));
$rows_where = implode(' OR ', array_map(function($row) {
    return "`$row` LIKE ?";
}, $rows));
$sql_args = array_map(function($row) use ($search) {
    return "%$search%";
}, $rows);

$sql = "SELECT $rows_select, $GEOPOSITION, (SQRT(POW(ST_Y(`geoposition`) - ?, 2) + POW(ST_X(`geoposition`) - ?, 2)) * 111139) AS distance FROM `urbanature`.`$table`
WHERE ($rows_where)
ORDER BY distance, RAND() ASC
LIMIT $limit";


header('Content-Type: application/json');

try {
    $data = call_sql($sql, [$lat, $lng, ...$sql_args,]);
    if(!empty($data)) {
        $res = [];
        foreach($data as $key => $value) {
            $res[$key] = nest_array($value);
        }
        echo json_encode($res);
    } else {
        echo json_encode([
            'error' => "No data",
        ]);
    }    
} catch(PDOException $e) {
    switch($e->getCode()) {
        case '42S02':
            $closest = get_closest_table($table);
            echo json_encode([
                'error' => "Table `$table` does not exist",
                'attempted_' => $table,
                'correction' => $closest['table'] ?? false,
            ]);
            break;
        default:
            echo json_encode([
                'error' => $e->getMessage(),
            ]);
            break;
    }
}
