<?php

header('Content-type: application/vnd.api+json');
header('Access-Control-Allow-Origin: *');

/**
 * Use this to set up local db.
 */

include_once '../Database.php';

$db = new Database();

if ( $db->setup() ) echo json_encode(
    array(
        'message' => 'Setup complete',
        'err' => false
    )
);