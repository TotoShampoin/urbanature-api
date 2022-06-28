<?php
function get_sid($table, $data) {
    $sid = [$table];
    switch($table) {
        case "arbres":
            $key = $data['info.genre'];
            break;
        case "arbres-remarquables":
            $key = $data['info.genre'];
            break;
        case "espaces-verts":
            $key = $data['info.categorie'];
            break;
        case "jardins-partages":
            $key = $data['info.type_jardin'];
            break;
        case "textes":
            $key = $data['info.author'];
            break;
    }
    $sid[] = trim($key);
    $sid[] = $data['id'];
    // return implode(",", $sid);
    return $sid;
}
