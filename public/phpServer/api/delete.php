<?php

header('Content-type: application/vnd.api+json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-type,
 Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../Database.php';
include_once '../ModelTasks.php';

$database = new Database();
$db = $database->connect();
$task = new ModelTasks($db);

$data = json_decode(file_get_contents("php://input"));
$task->id = $data->id;

if($task->delete()) {
    echo json_encode(
        array(
            'message' => 'Task whit the id of ' . $task->id . ' deleted!',
            'err' => false
            )
    );
}