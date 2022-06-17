<?php
include "./secret/db.php";

$GEOPOSITION = "ST_Y(`geoposition`) AS `geopos.lat`, ST_X(`geoposition`) AS `geopos.lng`";

function call_sql(string $req, array $args = []) {
    $pdo = $GLOBALS['pdo'];
    $stmt = $pdo->prepare($req);
    $stmt->execute($args);
    $result = $stmt->fetchAll();
    // rid of all number keys
    foreach($result as $key => $value) {
        foreach($value as $k => $v) {
            if(is_numeric($k)) {
                unset($result[$key][$k]);
            }
        }
    }
    return $result;
}

/**
 * input: [
 *   {"id": 5, "info.data": "something", "meta.bool": true},
 * ]
 * output: [
 *  {"id": 5, "info": {"data": "something"}, "meta": {"bool": true}},
 * ]
 */
function nest_array(array $array) {
    $result = [];
    foreach($array as $key => $value) {
        $path = explode('.', $key);
        if(count($path) <= 1) {
            $result[$key] = $value;
        } else {
            if(!isset($result[$path[0]])) {
                $result[$path[0]] = [];
            }
            $current = &$result[$path[0]];
            for($i = 1; $i < count($path); $i++) {
                $current[$path[$i]] = [];
                $current = &$current[$path[$i]];
            }
            $current = $value;
        }
    }
    return $result;
}

function get_closest_table($table) {
    $tables = call_sql("SHOW TABLES FROM `urbanature`");
    $distances = array_map(function($t) use ($table) {
        return [
            'table' => $t['Tables_in_urbanature'],
            'distance' => levenshtein($t['Tables_in_urbanature'], $table),
        ];
    }, $tables);
    return array_reduce($distances, function($a, $b) {
        return $a['distance'] < $b['distance'] ? $a : $b;
    }, ['table' => '', 'distance' => PHP_INT_MAX]);
}
