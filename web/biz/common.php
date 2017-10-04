<?php
function getDb(){
    //return new ezSQL_mysqli(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
    return new ezSQL_sqlite3('./db/','db-droid.db'); 
}

function dbTest() {
    $db = getDb();

    $query = "SELECT * FROM DtEmployee;";
    $result = $db->get_results($query);
    if (APP_DEBUG) {
        $db->vardump($result);
    }
    return $result;
}