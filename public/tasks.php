<?php
header('Content-type: application/vnd.api+json');
header('Access-Control-Allow-Origin: *');

$host = "localhost";
$user = "adrian";
$password = "asdasd321";
$dbname = "tasktracker";
$id = '';

$con = mysqli_connect($host, $user, $password, $dbname);

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));


if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


switch ($method) {
    case 'GET':
        $id = $_GET['id'];
        $sql = "select * from tasks" . ($id ? " where id=$id" : '');
        break;
    case 'POST':
        $task = $_POST["task"];
        $owner = $_POST["owner"];
        $sql = "insert into tasks (task, owner, doing, done, time) values ('$task', '$owner', false, false, 0)";
        break;
    case 'DELETE':
        break;
}

// run SQL statement
$result = mysqli_query($con, $sql);

// die if SQL statement failed
if (!$result) {
    http_response_code(404);
    die(mysqli_error($con));
}

if ($method == 'GET') {
    if (!$id) echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
        echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    if (!$id) echo ']';
} elseif ($method == 'POST') {
    echo json_encode($result);
} else {
    echo mysqli_affected_rows($con);
}

$con->close();