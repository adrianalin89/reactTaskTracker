<?php

header('Content-type: application/vnd.api+json');
header('Access-Control-Allow-Origin: *');

include_once '../Database.php';
include_once '../ModelTasks.php';

$database = new Database();
$db = $database->connect();

$tasks = new ModelTasks($db);

$tasks->id = isset($_GET['id']) ? $_GET['id'] : null;
$result = $tasks->read();

if($result) {
    if($tasks->id) {
        echo json_encode([
            'id' =>   $result->id,
            'task' => $result->task,
            'owner' => $result->owner,
            'doing' => $result->doing,
            'done' => $result->done,
            'time' => $result->time
        ]);
    } else {
        $tasks_arr = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($tasks_arr, [
                'id' => $id,
                'task' => $task,
                'owner' => $owner,
                'doing' => $doing,
                'done' => $done,
                'time' => $time
            ]);
        }
        echo json_encode($tasks_arr);
    }
}