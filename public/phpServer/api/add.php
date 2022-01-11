<?php

header('Content-type: application/vnd.api+json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST+PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-type,
Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../Database.php';
include_once '../ModelTasks.php';

$database = new Database();
$db = $database->connect();

$task = new ModelTasks($db);

$data = json_decode(file_get_contents("php://input"));

$task->task = $data->task ?: '';
$task->owner = $data->owner ?: '';
$task->doing = $data->doing ?: 0;
$task->done = $data->done ?: 0;
$task->time = $data->time ?: 0;

if($task->create()) {
    echo json_encode(
        [
            'id' => $task->id,
            'task' => $task->task,
            'owner' => $task->owner,
            'doing' => $task->doing,
            'done' => $task->done,
            'time' => $task->time
        ]
    );
}